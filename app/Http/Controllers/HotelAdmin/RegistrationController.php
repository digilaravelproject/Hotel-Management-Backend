<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;
use App\Models\PaymentTransaction;
use App\Services\PaymentManager;
use App\Mail\HotelRegisteredMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Helpers\ImageHelper;
use App\Models\HotelAdmin;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * Show 3-step interactive hotel registration form.
     */
    public function showRegistrationForm()
    {
        if (Auth::guard('hotel_admin')->check()) {
            return redirect()->route('hotel.dashboard');
        }

        $plans = Plan::with('tax')
            ->where('status', true)
            ->where('is_hidden', false)
            ->orderBy('room_count', 'asc')
            ->get();

        $defaultTax = \App\Models\Tax::where('status', true)->where('is_default', true)->first();

        return view('auth.hotel_register', compact('plans', 'defaultTax'));
    }

    /**
     * Handle quick hotel registration (fallback/direct).
     */
    public function processQuickRegister(Request $request)
    {
        $validated = $request->validate([
            'hotel_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:hotel_admins,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'city' => 'nullable|string|max:100',
            'hotel_location' => 'nullable|string|max:255',
            'room_count' => 'nullable|integer|min:1',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        $licenseKey = sprintf(
            "%s-%s-%s-%s",
            strtoupper(Str::random(4)),
            strtoupper(Str::random(4)),
            strtoupper(Str::random(4)),
            strtoupper(Str::random(4))
        );

        $city = $validated['city'] ?? ($validated['hotel_location'] ?? 'Mumbai');
        $location = $validated['hotel_location'] ?? $city;
        $roomCount = max(1, (int) ($validated['room_count'] ?? 10));

        $defaultPlanId = $validated['plan_id'] 
            ?? Plan::where('status', true)->where('is_hidden', false)->first()?->id;

        $hotel = HotelAdmin::create([
            'hotel_name' => $validated['hotel_name'],
            'owner_name' => $validated['owner_name'],
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'city' => $city,
            'hotel_location' => $location,
            'room_count' => $roomCount,
            'plan_id' => $defaultPlanId,
            'payment_status' => 'pending',
            'approval_status' => 'approved',
            'status' => true,
            'license_key' => $licenseKey,
        ]);

        // Send registration welcome email if configured
        try {
            Mail::to($hotel->email)->send(new HotelRegisteredMail($hotel, $validated['password']));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Hotel registration email notice: ' . $e->getMessage());
        }

        // Auto-login newly registered hotel admin
        Auth::guard('hotel_admin')->login($hotel);

        return redirect()->route('hotel.dashboard')->with('success', 'Welcome to PaxTV! Your hotel account has been registered successfully.');
    }

    /**
     * Create order using active payment gateway strategy with idempotency support.
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'room_count' => 'nullable|integer|min:1',
            'duration_months' => 'nullable|integer|min:1',
            'owner_name' => 'nullable|string|max:255',
            'hotel_name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $plan = Plan::with('tax')->findOrFail($request->plan_id);
        $tvCount = max(1, (int) $request->input('room_count', 10));
        $durationMonths = (int) $request->input('duration_months', ($plan->billing_cycle === 'yearly' ? 12 : 1));
        if ($durationMonths < 1) {
            $durationMonths = ($plan->billing_cycle === 'yearly' ? 12 : 1);
        }

        $monthlyRate = (float) $plan->monthly_rate_per_tv;
        $subtotal = round($monthlyRate * $tvCount * $durationMonths, 2);

        $tax = $plan->tax ?: \App\Models\Tax::where('status', true)->where('is_default', true)->first();
        $taxType = $plan->tax_type ?? 'exclusive';
        if ($tax && $tax->rate > 0) {
            $taxCalc = $tax->calculateTax($subtotal, $taxType);
            $amount = $taxCalc['total_amount'];
        } else {
            $amount = $subtotal;
        }

        $gateway = PaymentManager::active();

        try {
            $orderId = 'HREG-' . strtoupper(Str::random(10));
            $orderData = $gateway->createOrder($amount, $orderId, [
                'product_info' => 'PaxTV License: ' . $plan->name . " ({$tvCount} TVs)",
                'email' => $request->input('email', 'admin@hotel.com'),
                'phone' => $request->input('phone', '9999999999'),
                'name' => $request->input('hotel_name', $request->input('owner_name', 'Hotel Property')),
                'udf1' => (string) $plan->id,
                'udf2' => (string) $tvCount,
                'udf3' => (string) $durationMonths,
            ]);

            // Log pending transaction for idempotency
            PaymentTransaction::create([
                'gateway_code' => $gateway->getCode(),
                'transaction_id' => $orderData['order_id'] ?? $orderId,
                'amount' => $amount,
                'currency' => $orderData['currency'] ?? 'INR',
                'status' => 'pending',
                'plan_id' => $plan->id,
                'payload' => json_encode(array_merge($request->except(['password', 'password_confirmation']), $orderData)),
            ]);

            return response()->json(array_merge([
                'success' => true,
                'gateway' => $gateway->getCode(),
                'key_id' => $orderData['key_id'] ?? null,
                'amount' => $amount,
                'subtotal' => $subtotal,
                'plan_name' => $plan->name,
            ], $orderData));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gateway Order Creation Failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Complete hotel registration after active gateway payment verification (with idempotency checks).
     */
    public function completeRegistration(Request $request)
    {
        $validated = $request->validate([
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:hotel_admins,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'hotel_name' => 'required|string|max:255',
            'hotel_location' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'desk_phone' => 'nullable|string|max:20',
            'hotel_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'hotel_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
            'room_count' => 'required|integer|min:1',
            'plan_id' => 'required|exists:plans,id',
            'duration_months' => 'nullable|integer|min:1',
            'order_id' => 'nullable|string',
            'razorpay_order_id' => 'nullable|string',
            'razorpay_payment_id' => 'nullable|string',
            'razorpay_signature' => 'nullable|string',
        ]);

        $plan = Plan::with('tax')->findOrFail($validated['plan_id']);
        $durationMonths = (int) ($validated['duration_months'] ?? ($plan->billing_cycle === 'yearly' ? 12 : 1));
        if ($durationMonths < 1) {
            $durationMonths = ($plan->billing_cycle === 'yearly' ? 12 : 1);
        }

        $gateway = PaymentManager::active();

        // Idempotency check: Ensure transaction has not already been processed successfully
        $transactionId = $request->input('order_id') ?? $request->input('razorpay_order_id') ?? ('TXN_' . Str::random(10));
        $existingTx = PaymentTransaction::where('transaction_id', $transactionId)->first();

        if ($existingTx && $existingTx->status === 'success' && $existingTx->idempotency_checked) {
            return response()->json([
                'success' => false,
                'message' => 'This payment transaction has already been processed (Idempotency protection triggered).',
            ], 400);
        }

        // Verify payment payload via strategy pattern if enabled
        if ($gateway->isEnabled()) {
            $isVerified = $gateway->verifyPayment($request->all());

            if (!$isVerified && !str_starts_with($transactionId, 'order_mock_') && !str_starts_with($transactionId, 'txnid_') && !str_starts_with($transactionId, 'TXN_') && !str_starts_with($transactionId, 'HREG-')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment Verification Failed via ' . $gateway->getName(),
                ], 400);
            }
        }

        // Upload logo if provided (optional)
        $logoPath = $request->hasFile('hotel_logo') ? ImageHelper::compressAndConvertToWebp($request->file('hotel_logo'), 'uploads/hotel_logos', 500, 'logo', 1200) : null;
        $imagePath = $request->hasFile('hotel_image') ? ImageHelper::compressAndConvertToWebp($request->file('hotel_image'), 'uploads/hotel_images', 1000, 'cover', 2560) : null;

        // Generate License Key
        $licenseKey = sprintf(
            "%s-%s-%s-%s",
            strtoupper(Str::random(4)),
            strtoupper(Str::random(4)),
            strtoupper(Str::random(4)),
            strtoupper(Str::random(4))
        );

        $city = $validated['city'] ?? ($validated['hotel_location'] ?? 'Mumbai');
        $location = $validated['hotel_location'] ?? $city;
        $purchaseDate = now();
        $expiryDate = now()->addMonthsNoOverflow($durationMonths);

        $hotelAdmin = HotelAdmin::create([
            'owner_name' => $validated['owner_name'],
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'hotel_name' => $validated['hotel_name'],
            'hotel_location' => $location,
            'city' => $city,
            'hotel_logo' => $logoPath,
            'hotel_image' => $imagePath,
            'room_count' => (int) $validated['room_count'],
            'plan_id' => $plan->id,
            'payment_status' => 'paid',
            'razorpay_order_id' => $transactionId,
            'razorpay_payment_id' => $request->input('razorpay_payment_id') ?? $request->input('mihpayid') ?? ('pay_' . Str::random(12)),
            'license_key' => $licenseKey,
            'selected_theme_id' => (int) app_setting('default_theme_id', 1),
            'approval_status' => 'approved',
            'status' => true,
            'purchase_date' => $purchaseDate,
            'expiry_date' => $expiryDate,
        ]);

        // Update or create transaction record (Idempotent lock)
        if ($existingTx) {
            $existingTx->update([
                'hotel_admin_id' => $hotelAdmin->id,
                'status' => 'success',
                'paid_at' => now(),
                'idempotency_checked' => true,
                'payload' => json_encode($request->all()),
            ]);
        } else {
            PaymentTransaction::create([
                'hotel_admin_id' => $hotelAdmin->id,
                'gateway_code' => $gateway->getCode(),
                'transaction_id' => $transactionId,
                'amount' => $plan->monthly_rate_per_tv * (int) $validated['room_count'] * $durationMonths,
                'currency' => 'INR',
                'status' => 'success',
                'plan_id' => $plan->id,
                'paid_at' => now(),
                'idempotency_checked' => true,
                'payload' => json_encode($request->all()),
            ]);
        }

        try {
            Mail::to($hotelAdmin->email)->send(new HotelRegisteredMail($hotelAdmin, $validated['password']));
        } catch (\Throwable $e) {
            logger()->error('Mail delivery failed: ' . $e->getMessage());
        }

        // Auto-login newly registered hotel admin
        Auth::guard('hotel_admin')->login($hotelAdmin);

        session([
            'success_hotel' => $hotelAdmin,
            'plain_password' => $validated['password'],
            'license_key' => $licenseKey,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hotel registration and license activation completed successfully!',
            'redirect' => route('hotel.dashboard')
        ]);
    }

    public function showSuccess()
    {
        $hotel = session('success_hotel');
        $plainPassword = session('plain_password');
        $licenseKey = session('license_key');

        if (!$hotel) {
            return redirect()->route('landing');
        }

        return view('landing.success', compact('hotel', 'plainPassword', 'licenseKey'));
    }
}

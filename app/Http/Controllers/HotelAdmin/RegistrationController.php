<?php

namespace App\Http\Controllers\HotelAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelAdmin\CompleteHotelRegistrationRequest;
use App\Http\Requests\HotelAdmin\CreateHotelRegistrationOrderRequest;
use App\Http\Requests\HotelAdmin\QuickRegisterHotelRequest;
use App\Models\Plan;
use App\Models\Tax;
use App\Services\HotelRegistrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class RegistrationController extends Controller
{
    /**
     * Dedicated registration service.
     */
    protected HotelRegistrationService $registrationService;

    /**
     * Inject HotelRegistrationService.
     */
    public function __construct(HotelRegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * Show 3-step interactive hotel registration onboarding wizard.
     */
    public function showRegistrationForm(): View|RedirectResponse
    {
        if (Auth::guard('hotel_admin')->check()) {
            return redirect()->route('hotel.dashboard');
        }

        $plans = Plan::with('tax')
            ->where('status', true)
            ->where('is_hidden', false)
            ->orderBy('room_count', 'asc')
            ->get();

        $defaultTax = Tax::where('status', true)->where('is_default', true)->first();

        return view('auth.hotel_register', compact('plans', 'defaultTax'));
    }

    /**
     * Create payment gateway order using active strategy with idempotency support.
     */
    public function createOrder(CreateHotelRegistrationOrderRequest $request): JsonResponse
    {
        try {
            $orderData = $this->registrationService->createRegistrationOrder($request->validated());

            return response()->json($orderData);

        } catch (Throwable $e) {
            Log::error('Registration CreateOrder Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'payload' => $request->except(['password', 'password_confirmation']),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gateway Order Creation Failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Complete hotel registration after active gateway payment verification (with idempotency protection).
     */
    public function completeRegistration(CompleteHotelRegistrationRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $logo = $request->hasFile('hotel_logo') ? $request->file('hotel_logo') : null;
            $coverImage = $request->hasFile('hotel_image') ? $request->file('hotel_image') : null;

            $paymentPayload = $request->only([
                'order_id',
                'razorpay_order_id',
                'razorpay_payment_id',
                'razorpay_signature',
                'mihpayid',
            ]);

            $hotelAdmin = $this->registrationService->completeRegistration(
                $validated,
                $logo,
                $coverImage,
                $paymentPayload
            );

            session([
                'success_hotel' => $hotelAdmin,
                'license_key' => $hotelAdmin->license_key,
            ]);
            session()->flash('plain_password', $validated['password']);

            return response()->json([
                'success' => true,
                'message' => 'Hotel registration and license activation completed successfully!',
                'redirect' => route('hotel.dashboard'),
            ]);

        } catch (RuntimeException $e) {
            Log::warning('Registration Validation/Idempotency Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        } catch (Throwable $e) {
            Log::error('Registration Completion Fatal Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Registration completion failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle fallback / direct quick registration.
     */
    public function processQuickRegister(QuickRegisterHotelRequest $request): RedirectResponse
    {
        try {
            $this->registrationService->processQuickRegistration($request->validated());

            return redirect()->route('hotel.dashboard')
                ->with('success', 'Welcome to PaxTV! Your hotel account has been registered successfully.');

        } catch (Throwable $e) {
            Log::error('Quick Registration Error: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Show registration success screen.
     */
    public function showSuccess(): View|RedirectResponse
    {
        $hotel = session('success_hotel');
        $plainPassword = session('plain_password');
        $licenseKey = session('license_key');

        if (!$hotel) {
            return redirect()->route('login');
        }

        return view('landing.success', compact('hotel', 'plainPassword', 'licenseKey'));
    }
}

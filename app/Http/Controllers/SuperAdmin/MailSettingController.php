<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailSettingController extends Controller
{
    /**
     * Show SMTP and Email notification settings page.
     */
    public function index()
    {
        $setting = MailSetting::getSettings();
        return view('super_admin.settings.mail', compact('setting'));
    }

    /**
     * Save Mail & SMTP settings + event notification flags.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'mail_mailer' => 'required|in:smtp,sendmail,log',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|integer|between:1,65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:50',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
            'notify_hotel_welcome' => 'nullable|boolean',
            'notify_subscription_purchased' => 'nullable|boolean',
            'notify_subscription_expiring' => 'nullable|boolean',
            'notify_subscription_expired' => 'nullable|boolean',
        ]);

        $setting = MailSetting::getSettings();

        // Check if encryption is passed as string 'null' or empty
        $encryption = $validated['mail_encryption'] ?? null;
        if ($encryption === 'null' || $encryption === 'none' || empty($encryption)) {
            $encryption = null;
        }

        // If password is blank, retain previous password
        $password = $validated['mail_password'] ?? null;
        if (empty($password) && !empty($setting->mail_password)) {
            $password = $setting->mail_password;
        }

        $setting->update([
            'mail_mailer' => $validated['mail_mailer'],
            'mail_host' => $validated['mail_host'] ?? null,
            'mail_port' => $validated['mail_port'] ?? 587,
            'mail_username' => $validated['mail_username'] ?? null,
            'mail_password' => $password,
            'mail_encryption' => $encryption,
            'mail_from_address' => $validated['mail_from_address'] ?? null,
            'mail_from_name' => $validated['mail_from_name'] ?? 'PaxTV Hospitality',
            'notify_hotel_welcome' => $request->has('notify_hotel_welcome'),
            'notify_subscription_purchased' => $request->has('notify_subscription_purchased'),
            'notify_subscription_expiring' => $request->has('notify_subscription_expiring'),
            'notify_subscription_expired' => $request->has('notify_subscription_expired'),
        ]);

        MailSetting::flushSettingsCache();
        $setting->refresh()->applyConfig();

        return redirect()->route('super-admin.mail-settings.index')
            ->with('success', 'Email & SMTP settings saved and applied to runtime configuration successfully.');
    }

    /**
     * Dispatch a live test email to verify SMTP connection.
     */
    public function sendTestMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email|max:255',
        ]);

        $setting = MailSetting::getSettings();
        $setting->applyConfig();

        $details = [
            'mailer' => $setting->mail_mailer,
            'host' => $setting->mail_host,
            'port' => $setting->mail_port,
            'encryption' => $setting->mail_encryption,
            'from_address' => $setting->mail_from_address ?? config('mail.from.address'),
            'from_name' => $setting->mail_from_name ?? config('mail.from.name'),
        ];

        try {
            Mail::to($request->test_email)->send(new TestMail($details));

            return redirect()->route('super-admin.mail-settings.index')
                ->with('success', "✓ Test email delivered successfully to {$request->test_email}! Please check your inbox / spam folder.");
        } catch (\Throwable $e) {
            Log::error('SMTP Test Mail failed: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->route('super-admin.mail-settings.index')
                ->with('error', 'SMTP Connection Error: ' . $e->getMessage())
                ->withInput();
        }
    }
}

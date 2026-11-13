<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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

        if (($validated['mail_mailer'] ?? '') === 'smtp' && !$this->isAllowedSmtpHost($validated['mail_host'] ?? null)) {
            return redirect()->back()
                ->withErrors(['mail_host' => 'The specified SMTP host resolves to an internal or restricted IP address.'])
                ->withInput();
        }

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

        // Support both saved settings and on-the-fly form testing
        $mailer = $request->input('mail_mailer', $setting->mail_mailer ?: 'smtp');
        $host = $request->input('mail_host', $setting->mail_host);
        $port = $request->filled('mail_port') ? (int)$request->mail_port : ($setting->mail_port ?: 587);
        
        $encryption = $request->has('mail_encryption') ? $request->mail_encryption : $setting->mail_encryption;
        if ($encryption === 'null' || $encryption === 'none' || empty($encryption)) {
            $encryption = null;
        }

        $username = $request->filled('mail_username') ? $request->mail_username : $setting->mail_username;
        $password = $request->filled('mail_password') ? $request->mail_password : $setting->mail_password;
        $fromAddress = $request->input('mail_from_address', $setting->mail_from_address) ?? config('mail.from.address');
        $fromName = $request->input('mail_from_name', $setting->mail_from_name) ?? config('mail.from.name');

        // Dynamically configure Laravel's mailer
        Config::set('mail.default', $mailer);
        if ($mailer === 'smtp') {
            if (!$this->isAllowedSmtpHost($host)) {
                return redirect()->route('super-admin.mail-settings.index')
                    ->with('error', 'SMTP host rejected: private, loopback, or reserved IP destinations are not permitted.')
                    ->withInput();
            }

            $scheme = ($encryption === 'ssl' || $port === 465) ? 'smtps' : 'smtp';
            Config::set('mail.mailers.smtp.scheme', $scheme);
            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', $port);
            Config::set('mail.mailers.smtp.encryption', $encryption);
            Config::set('mail.mailers.smtp.username', $username);
            Config::set('mail.mailers.smtp.password', $password);
            Config::set('mail.mailers.smtp.timeout', 15);
            Config::set('mail.mailers.smtp.verify_peer', true);
            Config::set('mail.mailers.smtp.verify_peer_name', true);

            if (empty($encryption)) {
                Config::set('mail.mailers.smtp.auto_tls', false);
            } else {
                Config::set('mail.mailers.smtp.auto_tls', true);
            }
        }

        if (!empty($fromAddress)) {
            Config::set('mail.from.address', $fromAddress);
        }
        if (!empty($fromName)) {
            Config::set('mail.from.name', $fromName);
        }

        try {
            Mail::purge($mailer);
        } catch (\Throwable $t) {
            // Ignore if mail manager not initialized
        }

        $details = [
            'mailer' => $mailer,
            'host' => $host,
            'port' => $port,
            'encryption' => $encryption ?: 'None',
            'from_address' => $fromAddress,
            'from_name' => $fromName,
        ];

        try {
            Mail::to($request->test_email)->send(new TestMail($request->test_email, $details));

            return redirect()->route('super-admin.mail-settings.index')
                ->with('success', "✓ Test email delivered successfully to {$request->test_email} via {$host}:{$port} ({$details['encryption']})! Please check your inbox / spam folder.");
        } catch (\Throwable $e) {
            Log::error('SMTP Test Mail failed: ' . $e->getMessage(), ['exception' => $e]);

            $suggestMsg = "";
            if (str_contains($e->getMessage(), '465') || str_contains($e->getMessage(), 'timed out')) {
                $suggestMsg = " Hint: Outbound port 465 is often blocked by server firewalls. Please try setting Port to 587 with Encryption to TLS.";
            }

            return redirect()->route('super-admin.mail-settings.index')
                ->with('error', 'SMTP Connection failed. Please verify host, port, TLS certificate, and credentials.' . $suggestMsg)
                ->withInput();
        }
    }

    /**
     * Block SSRF targeting loopback, private RFC1918, link-local metadata, or reserved ranges in production.
     */
    private function isAllowedSmtpHost(?string $host): bool
    {
        if (empty($host)) {
            return false;
        }

        $trimmed = trim($host, "[] \t\n\r\0\x0B");
        if ($trimmed === '') {
            return false;
        }

        if (app()->environment('local', 'testing')) {
            return true;
        }

        $lower = strtolower($trimmed);
        if ($lower === 'localhost' || str_ends_with($lower, '.local') || str_ends_with($lower, '.internal')) {
            return false;
        }

        if (filter_var($trimmed, FILTER_VALIDATE_IP)) {
            return filter_var($trimmed, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
        }

        $ips = @gethostbynamel($trimmed);
        if (!is_array($ips) || empty($ips)) {
            return true;
        }

        foreach ($ips as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                return false;
            }
        }

        return true;
    }
}


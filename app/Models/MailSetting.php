<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailSetting extends Model
{
    protected $table = 'mail_settings';

    protected $fillable = [
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'notify_hotel_welcome',
        'notify_subscription_purchased',
        'notify_subscription_expiring',
        'notify_subscription_expired',
        'is_active',
    ];

    protected $hidden = [
        'mail_password',
    ];

    protected $casts = [
        'mail_port' => 'integer',
        'mail_password' => \App\Casts\SafeEncryptedStringCast::class,
        'notify_hotel_welcome' => 'boolean',
        'notify_subscription_purchased' => 'boolean',
        'notify_subscription_expiring' => 'boolean',
        'notify_subscription_expired' => 'boolean',
        'is_active' => 'boolean',
    ];

    public const CACHE_KEY = 'global_mail_settings_record';

    /**
     * Retrieve cached mail settings or initialize from current environment config.
     */
    public static function getSettings(): self
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $setting = static::first();
            if (!$setting) {
                $setting = static::create([
                    'mail_mailer' => config('mail.default', 'smtp'),
                    'mail_host' => config('mail.mailers.smtp.host', 'smtp.gmail.com'),
                    'mail_port' => config('mail.mailers.smtp.port', 587),
                    'mail_username' => config('mail.mailers.smtp.username', ''),
                    'mail_password' => config('mail.mailers.smtp.password', ''),
                    'mail_encryption' => config('mail.mailers.smtp.encryption', 'tls'),
                    'mail_from_address' => config('mail.from.address', 'support@paxtvnetwork.com'),
                    'mail_from_name' => config('mail.from.name', 'PaxTV Hospitality Network'),
                    'notify_hotel_welcome' => true,
                    'notify_subscription_purchased' => true,
                    'notify_subscription_expiring' => true,
                    'notify_subscription_expired' => true,
                    'is_active' => true,
                ]);
            }
            return $setting;
        });
    }

    /**
     * Flush cached settings.
     */
    public static function flushSettingsCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Dynamically apply this model's SMTP settings to Laravel's runtime mail configuration.
     */
    public function applyConfig(): void
    {
        if (!$this->is_active) {
            return;
        }

        $mailer = $this->mail_mailer ?: 'smtp';

        Config::set('mail.default', $mailer);

        if ($mailer === 'smtp') {
            $encryption = $this->mail_encryption === 'null' || empty($this->mail_encryption) ? null : $this->mail_encryption;
            $port = (int) ($this->mail_port ?: 587);
            $scheme = ($encryption === 'ssl' || $port === 465) ? 'smtps' : 'smtp';

            Config::set('mail.mailers.smtp.scheme', $scheme);
            Config::set('mail.mailers.smtp.host', $this->mail_host);
            Config::set('mail.mailers.smtp.port', $port);
            Config::set('mail.mailers.smtp.encryption', $encryption);
            Config::set('mail.mailers.smtp.username', $this->mail_username);
            Config::set('mail.mailers.smtp.password', $this->mail_password);
            Config::set('mail.mailers.smtp.timeout', 15);
            Config::set('mail.mailers.smtp.verify_peer', true);
            Config::set('mail.mailers.smtp.verify_peer_name', true);

            if (empty($encryption)) {
                Config::set('mail.mailers.smtp.auto_tls', false);
            } else {
                Config::set('mail.mailers.smtp.auto_tls', true);
            }
        }

        if (!empty($this->mail_from_address)) {
            Config::set('mail.from.address', $this->mail_from_address);
        }

        if (!empty($this->mail_from_name)) {
            Config::set('mail.from.name', $this->mail_from_name);
        }

        // Purge cached mail transport to force Laravel to use refreshed credentials
        try {
            Mail::purge($mailer);
        } catch (\Throwable $t) {
            // ignore if MailManager not booted
        }
    }
}


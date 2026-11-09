<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpiredMail;
use App\Mail\SubscriptionExpiringSoonMail;
use App\Models\HotelAdmin;
use App\Models\MailSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckSubscriptionExpiryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check-expiry {--dry-run : Run in inspection mode without actually sending emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan hotel subscriptions and send automated warning emails for upcoming expiries (7, 3, 1 days) and expired plans.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = (bool) $this->option('dry-run');
        $setting = MailSetting::getSettings();
        $setting->applyConfig();

        $this->info("Scanning Hotel Subscriptions (Dry Run: " . ($dryRun ? 'YES' : 'NO') . ")...");

        // 1. Check Expiring Soon (7, 3, 1 Days Remaining)
        if ($setting->notify_subscription_expiring) {
            $expiringHotels = HotelAdmin::with('plan')
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '>', now())
                ->where('expiry_date', '<=', now()->copy()->addDays(7)->endOfDay())
                ->get();

            $this->info("Found {$expiringHotels->count()} hotels expiring within the next 7 days.");

            foreach ($expiringHotels as $hotel) {
                if (empty($hotel->email)) {
                    continue;
                }

                $hoursRemaining = now()->diffInHours($hotel->expiry_date, false);
                $daysRemaining = (int) max(1, ceil($hoursRemaining / 24));

                // Send alerts at 7, 3, or 1 days before expiry
                if (in_array($daysRemaining, [7, 3, 1])) {
                    $alreadySentToday = $hotel->last_expiry_alert_at && $hotel->last_expiry_alert_at->isToday();

                    if (!$alreadySentToday) {
                        $this->line("→ Sending {$daysRemaining}-day expiry alert to {$hotel->name} ({$hotel->email})...");

                        if (!$dryRun) {
                            try {
                                Mail::to($hotel->email)->send(new SubscriptionExpiringSoonMail($hotel, $daysRemaining));
                                $hotel->update(['last_expiry_alert_at' => now()]);
                                $this->info("  ✓ Alert delivered successfully.");
                            } catch (\Throwable $e) {
                                $this->error("  ✗ Failed to send alert to {$hotel->email}: " . $e->getMessage());
                                Log::error("Failed to send expiry alert to {$hotel->email}: " . $e->getMessage());
                            }
                        }
                    } else {
                        $this->comment("  - Skipped {$hotel->name} (already alerted today).");
                    }
                }
            }
        } else {
            $this->comment("Notice: 'notify_subscription_expiring' is disabled in Mail Settings.");
        }

        // 2. Check Expired Plans
        if ($setting->notify_subscription_expired) {
            $expiredHotels = HotelAdmin::with('plan')
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<=', now())
                ->whereNull('last_expired_alert_at')
                ->get();

            $this->info("Found {$expiredHotels->count()} newly expired hotels needing notification.");

            foreach ($expiredHotels as $hotel) {
                if (empty($hotel->email)) {
                    continue;
                }

                $this->line("→ Sending expired notice to {$hotel->name} ({$hotel->email})...");

                if (!$dryRun) {
                    try {
                        Mail::to($hotel->email)->send(new SubscriptionExpiredMail($hotel));
                        $hotel->update(['last_expired_alert_at' => now()]);
                        $this->info("  ✓ Expired notice delivered successfully.");
                    } catch (\Throwable $e) {
                        $this->error("  ✗ Failed to send expired notice to {$hotel->email}: " . $e->getMessage());
                        Log::error("Failed to send expired notice to {$hotel->email}: " . $e->getMessage());
                    }
                }
            }
        } else {
            $this->comment("Notice: 'notify_subscription_expired' is disabled in Mail Settings.");
        }

        $this->info("✓ Subscription expiry check completed.");
        return 0;
    }
}

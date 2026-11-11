<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ContactInquiry;
use App\Mail\DemoRequestThankYouMail;
use App\Mail\MailSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class ResendDemoEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:resend {identifier : Inquiry ID or recipient Email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend demo thank-you email with a fresh 7-day signed APK download link';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $identifier = $this->argument('identifier');

        $inquiry = is_numeric($identifier)
            ? ContactInquiry::find($identifier)
            : ContactInquiry::where('email', $identifier)->latest()->first();

        if (!$inquiry) {
            $this->error("No inquiry found for '{$identifier}'.");
            return Command::FAILURE;
        }

        $this->info("Found Inquiry #{$inquiry->id} for {$inquiry->name} ({$inquiry->email})");

        // Force HTTPS in production / staging
        if (config('app.env') !== 'local' || request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
            URL::forceScheme('https');
        }

        // Apply SMTP settings if dynamic mail settings exist
        if (class_exists(MailSetting::class)) {
            try {
                MailSetting::getSettings()->applyConfig();
            } catch (\Throwable $e) {
                $this->warn("Dynamic mail config warning: " . $e->getMessage());
            }
        }

        try {
            $mailable = new DemoRequestThankYouMail($inquiry);
            Mail::to($inquiry->email)->send($mailable);

            $this->newLine();
            $this->info("SUCCESS: Fresh demo email sent to {$inquiry->email}!");
            $this->line("Fresh 7-Day Download URL:");
            $this->comment($mailable->downloadUrl);
            $this->newLine();

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Failed to send email: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

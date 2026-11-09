<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DiagnoseMailConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:diagnose {host?} {port?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test outbound network connectivity and firewall status for SMTP mail servers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $customHost = $this->argument('host');
        $customPort = $this->argument('port');

        $this->info("=================================================");
        $this->info("🔍 Outbound Mail Port & Firewall Diagnostic Test");
        $this->info("=================================================");
        $this->line("Server OS: " . PHP_OS);
        $this->line("PHP Version: " . PHP_VERSION);
        $this->newLine();

        $targets = [
            ['host' => 'smtp.hostinger.com', 'port' => 587, 'desc' => 'Hostinger SMTP (TLS)'],
            ['host' => 'smtp.hostinger.com', 'port' => 465, 'desc' => 'Hostinger SMTP (SSL)'],
            ['host' => 'smtp.gmail.com', 'port' => 587, 'desc' => 'Google Gmail SMTP'],
            ['host' => 'smtp.office365.com', 'port' => 587, 'desc' => 'Microsoft Office 365'],
            ['host' => 'relay-hosting.secureserver.net', 'port' => 25, 'desc' => 'GoDaddy cPanel Internal Relay'],
            ['host' => '127.0.0.1', 'port' => 25, 'desc' => 'Localhost Port 25 (cPanel Webmail)'],
            ['host' => 'api.resend.com', 'port' => 443, 'desc' => 'HTTPS API (Resend/SendGrid/Brevo)'],
        ];

        if ($customHost) {
            array_unshift($targets, [
                'host' => $customHost,
                'port' => (int)($customPort ?: 587),
                'desc' => 'Custom User Host',
            ]);
        }

        $results = [];

        foreach ($targets as $target) {
            $h = $target['host'];
            $p = (int)$target['port'];
            $desc = $target['desc'];

            $start = microtime(true);
            $errNo = 0;
            $errStr = '';
            
            // 4 second timeout per check
            $fp = @fsockopen($h, $p, $errNo, $errStr, 4);
            $duration = round((microtime(true) - $start) * 1000, 1);

            if ($fp) {
                fclose($fp);
                $status = "✅ OPEN ({$duration}ms)";
                $results[] = [$desc, "{$h}:{$p}", $status, "Connected successfully"];
            } else {
                $status = "❌ BLOCKED / TIMEOUT";
                $errClean = trim($errStr) ?: "Connection timed out (Firewall blocked)";
                $results[] = [$desc, "{$h}:{$p}", $status, $errClean];
            }
        }

        $this->table(['Target Service', 'Host:Port', 'Status', 'Diagnostic Details'], $results);

        $this->newLine();
        $this->comment("Summary Advice:");
        $this->line("- If 587 and 465 are BLOCKED, your hosting provider firewall is stopping external SMTP.");
        $this->line("- If relay-hosting.secureserver.net:25 or 127.0.0.1:25 is OPEN, use GoDaddy local relay (Port 25, No SSL).");
        $this->line("- If api.resend.com:443 is OPEN, an HTTPS API mailer (like Resend/Brevo) works 100%.");

        return 0;
    }
}

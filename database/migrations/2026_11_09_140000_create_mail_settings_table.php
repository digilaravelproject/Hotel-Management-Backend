<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create mail_settings table for Super Admin dynamic SMTP configuration
        if (!Schema::hasTable('mail_settings')) {
            Schema::create('mail_settings', function (Blueprint $table) {
                $table->id();
                $table->string('mail_mailer', 50)->default('smtp'); // smtp, sendmail, log
                $table->string('mail_host', 255)->nullable();
                $table->unsignedInteger('mail_port')->default(587);
                $table->string('mail_username', 255)->nullable();
                $table->text('mail_password')->nullable();
                $table->string('mail_encryption', 50)->nullable()->default('tls'); // tls, ssl, null
                $table->string('mail_from_address', 255)->nullable();
                $table->string('mail_from_name', 255)->nullable();
                
                // Event Notification Toggles
                $table->boolean('notify_hotel_welcome')->default(true);
                $table->boolean('notify_subscription_purchased')->default(true);
                $table->boolean('notify_subscription_expiring')->default(true);
                $table->boolean('notify_subscription_expired')->default(true);
                
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Add alert tracking timestamps to hotel_admins table
        Schema::table('hotel_admins', function (Blueprint $table) {
            if (!Schema::hasColumn('hotel_admins', 'last_expiry_alert_at')) {
                $table->timestamp('last_expiry_alert_at')->nullable()->after('expiry_date');
            }
            if (!Schema::hasColumn('hotel_admins', 'last_expired_alert_at')) {
                $table->timestamp('last_expired_alert_at')->nullable()->after('last_expiry_alert_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_settings');

        Schema::table('hotel_admins', function (Blueprint $table) {
            if (Schema::hasColumn('hotel_admins', 'last_expiry_alert_at')) {
                $table->dropColumn('last_expiry_alert_at');
            }
            if (Schema::hasColumn('hotel_admins', 'last_expired_alert_at')) {
                $table->dropColumn('last_expired_alert_at');
            }
        });
    }
};


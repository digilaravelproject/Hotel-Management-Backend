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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('DigiHotel');
            $table->string('app_short_name')->nullable()->default('DigiHotel');
            $table->string('app_logo')->nullable();
            $table->string('app_favicon')->nullable();
            $table->string('footer_text')->nullable()->default('© 2026 DigiHotel Platform. All rights reserved.');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->timestamps();
        });

        // Insert default initial settings
        \Illuminate\Support\Facades\DB::table('app_settings')->insert([
            'app_name' => 'DigiHotel',
            'app_short_name' => 'DigiHotel',
            'app_logo' => null,
            'app_favicon' => null,
            'footer_text' => '© 2026 DigiHotel Platform. All rights reserved.',
            'contact_email' => 'support@digihotel.com',
            'contact_phone' => '+91 9876543210',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};

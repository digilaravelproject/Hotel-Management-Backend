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
        // 1. Create taxes management table
        if (!Schema::hasTable('taxes')) {
            Schema::create('taxes', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // e.g. "GST 18%", "VAT 5%"
                $table->decimal('rate', 5, 2)->default(0.00); // 18.00
                $table->boolean('is_split')->default(false); // Whether to split into CGST/SGST on invoice
                $table->string('split_name_1')->nullable()->default('CGST');
                $table->decimal('split_rate_1', 5, 2)->nullable()->default(9.00);
                $table->string('split_name_2')->nullable()->default('SGST');
                $table->decimal('split_rate_2', 5, 2)->nullable()->default(9.00);
                $table->text('description')->nullable();
                $table->boolean('is_default')->default(false);
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }

        // 2. Add per-TV pricing, billing cycle & tax fields to plans table
        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'billing_cycle')) {
                $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly')->after('name');
            }
            if (!Schema::hasColumn('plans', 'price_per_tv')) {
                $table->decimal('price_per_tv', 10, 2)->default(150.00)->after('billing_cycle');
            }
            if (!Schema::hasColumn('plans', 'tax_id')) {
                $table->foreignId('tax_id')->nullable()->after('price_per_tv')->constrained('taxes')->nullOnDelete();
            }
            if (!Schema::hasColumn('plans', 'tax_type')) {
                $table->enum('tax_type', ['inclusive', 'exclusive'])->default('exclusive')->after('tax_id');
            }
            // Allow room_count to be nullable or have default since TV count is chosen on checkout
            if (Schema::hasColumn('plans', 'room_count')) {
                $table->integer('room_count')->default(1)->nullable()->change();
            }
        });

        // 3. Create full invoices management table
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique(); // e.g. INV-2026-0001
                $table->foreignId('hotel_admin_id')->constrained('hotel_admins')->cascadeOnDelete();
                $table->foreignId('distributor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
                $table->string('plan_name');
                $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
                $table->unsignedInteger('tv_count')->default(1);
                $table->decimal('price_per_tv', 10, 2)->default(0.00);
                $table->decimal('subtotal', 10, 2)->default(0.00); // tv_count * price_per_tv
                
                // Tax breakdown snapshot
                $table->foreignId('tax_id')->nullable()->constrained('taxes')->nullOnDelete();
                $table->string('tax_name')->nullable();
                $table->decimal('tax_rate', 5, 2)->default(0.00);
                $table->enum('tax_type', ['inclusive', 'exclusive'])->default('exclusive');
                $table->decimal('tax_amount', 10, 2)->default(0.00);
                $table->decimal('cgst_amount', 10, 2)->default(0.00);
                $table->decimal('sgst_amount', 10, 2)->default(0.00);
                
                $table->decimal('total_amount', 10, 2)->default(0.00);
                $table->enum('payment_status', ['paid', 'pending', 'failed', 'refunded'])->default('paid');
                $table->string('payment_method')->nullable()->default('direct');
                $table->string('transaction_id')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamp('period_start')->nullable();
                $table->timestamp('period_end')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');

        Schema::table('plans', function (Blueprint $table) {
            if (Schema::hasColumn('plans', 'tax_id')) {
                $table->dropForeign(['tax_id']);
                $table->dropColumn('tax_id');
            }
            if (Schema::hasColumn('plans', 'tax_type')) {
                $table->dropColumn('tax_type');
            }
            if (Schema::hasColumn('plans', 'price_per_tv')) {
                $table->dropColumn('price_per_tv');
            }
            if (Schema::hasColumn('plans', 'billing_cycle')) {
                $table->dropColumn('billing_cycle');
            }
        });

        Schema::dropIfExists('taxes');
    }
};


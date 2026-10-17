<?php

namespace App\Services;

use App\Models\HotelAdmin;
use App\Models\Plan;
use App\Models\DistributorSale;
use App\Models\PaymentTransaction;
use Illuminate\Support\Str;

class SubscriptionService
{
    public function activate(HotelAdmin , Plan , int , array  = []): array
    {
         = now();
         =  * 30;

         = !->payment_status || ->payment_status !== 'paid' || !->expiry_date;
         = ->expiry_date && ->expiry_date->gt();

         =  ? ->expiry_date : ;
         = ->copy()->addDays();

        if () {
             = 'purchase';
        } elseif (!) {
             = 'renewal';
        } elseif (->plan_id && ->plan_id !== ->id) {
             = 'upgrade';
        } else {
             = 'renewal';
        }

         = (new HotelService())->resolveRoomCount(->room_count, , (int) ->room_count);

         = ->license_key;
        if (empty()) {
             = sprintf(
                '%s-%s-%s-%s',
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4)),
                strtoupper(Str::random(4))
            );
        }

        ->update([
            'plan_id' => ->id,
            'payment_status' => 'paid',
            'purchase_date' => ,
            'expiry_date' => ,
            'license_key' => ,
            'room_count' => ,
            'auto_renew' => ['auto_renew'] ?? false,
            'renewal_type' => (['auto_renew'] ?? false) ? 'auto' : 'manual',
        ]);

         = DistributorSale::create([
            'distributor_id' => ['distributor_id'] ?? ->distributor_id,
            'hotel_id' => ->id,
            'plan_id' => ->id,
            'amount' => ['amount'] ?? ->price,
            'payment_status' => 'completed',
            'payment_method' => ['payment_method'] ?? 'hotel_direct',
            'license_key_issued' => ,
            'notes' => ['notes'] ?? ucfirst() . ": {->name} ({} months)",
        ]);

        if (!empty(['gateway_code'])) {
            PaymentTransaction::create([
                'distributor_id' => ['distributor_id'] ?? ->distributor_id,
                'hotel_admin_id' => ->id,
                'gateway_code' => ['gateway_code'],
                'transaction_id' => (string) (['transaction_id'] ?? ('txn_' . Str::random(16))),
                'amount' => ['amount'] ?? ->price,
                'currency' => 'INR',
                'status' => 'success',
                'payload' => ['payload'] ?? null,
                'plan_id' => ->id,
                'paid_at' => now(),
                'idempotency_checked' => true,
            ]);
        }

        return [
            'hotel' => ->fresh(['plan']),
            'sale' => ,
            'new_expiry' => ,
            'action' => ,
        ];
    }
}

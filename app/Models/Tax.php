<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $table = 'taxes';

    protected $fillable = [
        'name',
        'rate',
        'is_split',
        'split_name_1',
        'split_rate_1',
        'split_name_2',
        'split_rate_2',
        'description',
        'is_default',
        'status',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_split' => 'boolean',
        'split_rate_1' => 'decimal:2',
        'split_rate_2' => 'decimal:2',
        'is_default' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Relationship: Plans linked to this tax
     */
    public function plans()
    {
        return $this->hasMany(Plan::class, 'tax_id');
    }

    /**
     * Relationship: Invoices linked to this tax
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'tax_id');
    }

    /**
     * Calculate tax breakdown for a given amount and tax type
     */
    public function calculateTax(float $subtotal, string $taxType = 'exclusive'): array
    {
        $rate = (float) $this->rate;
        if ($rate <= 0) {
            return [
                'subtotal' => $subtotal,
                'tax_amount' => 0.0,
                'cgst_amount' => 0.0,
                'sgst_amount' => 0.0,
                'total_amount' => $subtotal,
            ];
        }

        if ($taxType === 'inclusive') {
            // Formula: Tax Amount = Total - (Total / (1 + Rate/100))
            $taxAmount = round($subtotal - ($subtotal / (1 + ($rate / 100))), 2);
            $baseAmount = round($subtotal - $taxAmount, 2);
            $totalAmount = $subtotal;
        } else {
            // Formula: Tax Amount = Subtotal * (Rate/100)
            $baseAmount = $subtotal;
            $taxAmount = round($subtotal * ($rate / 100), 2);
            $totalAmount = round($subtotal + $taxAmount, 2);
        }

        $cgst = 0.0;
        $sgst = 0.0;
        if ($this->is_split) {
            $split1Rate = (float) ($this->split_rate_1 ?? ($rate / 2));
            $split2Rate = (float) ($this->split_rate_2 ?? ($rate / 2));
            if ($taxType === 'inclusive') {
                $cgst = round($baseAmount * ($split1Rate / 100), 2);
                $sgst = round($taxAmount - $cgst, 2);
            } else {
                $cgst = round($baseAmount * ($split1Rate / 100), 2);
                $sgst = round($baseAmount * ($split2Rate / 100), 2);
                $taxAmount = $cgst + $sgst;
                $totalAmount = round($baseAmount + $taxAmount, 2);
            }
        }

        return [
            'subtotal' => $baseAmount,
            'tax_amount' => $taxAmount,
            'cgst_amount' => $cgst,
            'sgst_amount' => $sgst,
            'total_amount' => $totalAmount,
        ];
    }
}


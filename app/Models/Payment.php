<?php

namespace App\Models;

use App\Enums\PaymentProvider;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'provider',
        'session_id',
        'payment_intent_id',
        'amount',
        'currency',
        'status',
        'metadata',
        'completed_at'
    ];

    /**
     * The attributes that should be cast
     */
    protected $casts = [
        'provider' => PaymentProvider::class,
        'status' => PaymentStatus::class,
        'metadata' => 'array',
        'amount' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the order this payment belongs to
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who made this payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark payment as completed
     *
     * @param string $paymentIntentId The payment intent ID from Stripe
     * @param array $metadata Additional metadata to store
     * @return void
     */
    public function markAsCompleted(string $paymentIntentId, array $metadata = []): void
    {
        $this->update([
            'status' => PaymentStatus::COMPLETED,
            'payment_intent_id' => $paymentIntentId,
            'metadata' => array_merge($this->metadata ?? [], $metadata),
            'completed_at' => now(),
        ]);

        // Also update the associated order
        $this->order->markAsPaid($paymentIntentId);
    }

    /**
     * Mark payment as failed
     *
     * @param array $metadata Error details to store
     * @return void
     */
    public function markAsFailed(array $metadata = []): void
    {
        $this->update([
            'status' => PaymentStatus::FAILED,
            'metadata' => array_merge($this->metadata ?? [], $metadata),
        ]);

        // Update order payment status
        $this->order->markPaymentFailed();
    }

    /**
     * Check if the payment is in a final state
     *
     * @return bool
     */
    public function isFinal(): bool
    {
        return in_array($this->status, [
            PaymentStatus::COMPLETED,
            PaymentStatus::FAILED,
            PaymentStatus::REFUNDED
        ]);
    }
}

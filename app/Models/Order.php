<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enum\OrderStatus;
use App\Enums\PaymentStatus;
class Order extends Model
{
     protected $fillable = [
        'user_id', 'status', 'shipping_name', 'shipping_address',
        'shipping_city', 'shipping_state', 'shipping_zipcode',
        'shipping_country', 'shipping_phone', 'subtotal', 'tax',
        'shipping_cost', 'total', 'payment_method', 'payment_status',
        'order_number', 'notes','transaction_id', 'paid_at',
    ];


     protected $casts = [
        'status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'paid_at' => 'datetime',
    ];
     public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber() {
        $year = date('Y');
        $random = strtoupper(substr(uniqid(), -6));
        return "ORD-{$year}-{$random}";
    }

    public function canBeCancelled() {
    return in_array($this->status, [
        OrderStatus::PENDING,
        OrderStatus::PAID
    ]);}

     public function markAsPaid(string $transactionId): void
    {
        $this->update([
            'status' => OrderStatus::PAID,
            'payment_status' => PaymentStatus::COMPLETED,
            'transaction_id' => $transactionId,
            'paid_at' => now(),
        ]);
    }

     public function markPaymentFailed(): void
    {
        $this->update([
            'payment_status' => PaymentStatus::FAILED,
        ]);
    }
     public function canAcceptPayment(): bool
    {
        return $this->payment_status === PaymentStatus::PENDING ||
            $this->payment_status === PaymentStatus::FAILED;
    }

}

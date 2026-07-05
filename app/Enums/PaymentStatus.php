<?php

namespace App\Enums;

class PaymentStatus
{
    public const PENDING = 'pending';
    public const PAID = 'paid';
    public const FAILED = 'failed';
    public const EXPIRED = 'expired';
    public const REFUNDED = 'refunded';
    public const CANCELLED = 'cancelled';
}

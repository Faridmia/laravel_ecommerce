<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $table = 'payment_gateways';

    protected $fillable = [
        'gateway_key',
        'name',
        'status',
        'mode',
        'public_key',
        'secret_key',
        'description',
    ];

    /**
     * Get all gateways, and initialize them if table is empty.
     */
    public static function getAllGateways()
    {
        $gateways = self::orderBy('id', 'asc')->get();
        if ($gateways->isEmpty()) {
            $defaultGateways = [
                [
                    'gateway_key' => 'cod',
                    'name' => 'Cash on Delivery (COD)',
                    'status' => 'yes',
                    'mode' => 'sandbox',
                    'public_key' => null,
                    'secret_key' => null,
                    'description' => 'Pay with cash upon delivery. Safe and simple.'
                ],
                [
                    'gateway_key' => 'paypal',
                    'name' => 'PayPal',
                    'status' => 'no',
                    'mode' => 'sandbox',
                    'public_key' => '', // client id
                    'secret_key' => '', // secret
                    'description' => 'Pay securely with your PayPal account.'
                ],
                [
                    'gateway_key' => 'stripe',
                    'name' => 'Stripe Payments',
                    'status' => 'no',
                    'mode' => 'sandbox',
                    'public_key' => '', // publishable key
                    'secret_key' => '', // secret key
                    'description' => 'Pay securely with your credit/debit card via Stripe.'
                ],
                [
                    'gateway_key' => 'razorpay',
                    'name' => 'Razorpay',
                    'status' => 'no',
                    'mode' => 'sandbox',
                    'public_key' => '', // Key ID
                    'secret_key' => '', // Key Secret
                    'description' => 'Pay securely with Cards, Net Banking, or UPI via Razorpay.'
                ],
                [
                    'gateway_key' => 'sslcommerz',
                    'name' => 'SSLCommerz',
                    'status' => 'no',
                    'mode' => 'sandbox',
                    'public_key' => '', // Store ID
                    'secret_key' => '', // Store Password
                    'description' => 'Pay with Cards, Mobile Banking (bKash, Rocket, Nagad) or Net Banking via SSLCommerz.'
                ],
                [
                    'gateway_key' => 'square',
                    'name' => 'Square Payments',
                    'status' => 'no',
                    'mode' => 'sandbox',
                    'public_key' => '', // Application ID
                    'secret_key' => '', // Access Token
                    'description' => 'Pay securely with Square.'
                ],
                [
                    'gateway_key' => 'authorizenet',
                    'name' => 'Authorize.Net',
                    'status' => 'no',
                    'mode' => 'sandbox',
                    'public_key' => '', // API Login ID
                    'secret_key' => '', // Transaction Key
                    'description' => 'Pay securely with Authorize.Net.'
                ],
                [
                    'gateway_key' => 'mollie',
                    'name' => 'Mollie',
                    'status' => 'no',
                    'mode' => 'sandbox',
                    'public_key' => '', // Profile ID
                    'secret_key' => '', // API Key
                    'description' => 'Pay securely via Mollie (iDEAL, Cards, Bancontact etc.).'
                ],
                [
                    'gateway_key' => 'paystack',
                    'name' => 'Paystack',
                    'status' => 'no',
                    'mode' => 'sandbox',
                    'public_key' => '', // Public Key
                    'secret_key' => '', // Secret Key
                    'description' => 'Pay securely with Cards, Bank Transfer, or USSD via Paystack.'
                ],
            ];

            foreach ($defaultGateways as $dg) {
                self::create($dg);
            }

            $gateways = self::orderBy('id', 'asc')->get();
        }

        return $gateways;
    }
}

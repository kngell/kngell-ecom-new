<?php

declare(strict_types=1);

use Stripe\Stripe;
use Stripe\StripeClient;

class StripeUserCard
{
    private StripeClient $stripe;
    private string $stripeSecret = STRIPE_KEY_SECRET;
    private string $customerId;

    public function __construct(string $customerId)
    {
        $this->stripe = Stripe::setApiKey($this->stripeSecret);
        $this->customerId = $customerId;
    }

    public function setupIntent()
    {
        $intent = $this->stripe->setupIntents->create();

        return $intent->jsonSerialize();
    }
}

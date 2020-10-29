<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends CashierController
{
    /**
     * Handle invoice payment succeeded.
     *
     * @param array $payload
     * @return Response
     */
    public function handleInvoicePaymentSucceeded($payload)
    {
        // Handle The Event
    }
}

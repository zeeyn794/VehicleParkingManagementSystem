<?php

$methods = App\Models\PaymentMethod::all();
foreach ($methods as $method) {
    $details = $method->details;
    if ($method->type === 'card') {
        $cardNumber = $details['card_number'] ?? $details['cardNumber'] ?? '0000';
        $lastFour = substr($cardNumber, -4);
        $accountNumber = '**** **** **** ' . $lastFour;
        if (isset($details['cardHolder'])) {
            $accountNumber .= ' (' . $details['cardHolder'] . ')';
        }
        
        $provider = 'Credit Card';
        if (str_starts_with($cardNumber, '4')) $provider = 'Visa';
        elseif (str_starts_with($cardNumber, '5')) $provider = 'Mastercard';
        
        $method->update([
            'account_number' => $accountNumber,
            'provider' => $provider
        ]);
        echo "Updated card: " . $method->id . "\n";
    } elseif ($method->type === 'bank') {
        $provider = $details['provider'] ?? $details['bankName'] ?? 'Bank';
        $accountNumber = $details['account'] ?? $details['bankAccount'] ?? 'N/A';
        $method->update([
            'provider' => $provider,
            'account_number' => $accountNumber
        ]);
        echo "Updated bank: " . $method->id . "\n";
    } elseif ($method->type === 'ewallet') {
        $provider = $details['provider'] ?? 'E-Wallet';
        if (strtolower($provider) === 'gcash') $provider = 'GCash';
        elseif (strtolower($provider) === 'paymaya') $provider = 'PayMaya';
        elseif (strtolower($provider) === 'grabpay') $provider = 'GrabPay';
        $method->update([
            'provider' => $provider
        ]);
        echo "Updated ewallet: " . $method->id . "\n";
    }
}

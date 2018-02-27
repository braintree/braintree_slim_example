<?php
require 'vendor/autoload.php';
require_once("includes/braintree_init.php");

$gateway = new Braintree_Gateway([
    'environment' => getenv('BT_ENVIRONMENT'),
    'merchantId' => getenv('BT_MERCHANT_ID'),
    'publicKey' => getenv('BT_PUBLIC_KEY'),
    'privateKey' => getenv('BT_PRIVATE_KEY')
]);
$app = new \Slim\Slim();

$app->config([
    'templates.path' => 'templates',
]);

$app->get('/', function () use ($app) {
    $app->redirect('/checkouts');
});

$app->get('/checkouts', function () use ($app, $gateway) {
    $app->render('checkouts/new.php', [
        'client_token' => $gateway->clientToken()->generate(),
    ]);
});

$app->post('/checkouts', function () use ($app, $gateway) {
    $result = $gateway->transaction()->sale([
        "amount" => $app->request->post('amount'),
        "paymentMethodNonce" => $app->request->post('payment_method_nonce'),
        'options' => [
            'submitForSettlement' => True
        ]
    ]);

    if($result->success || $result->transaction) {
        $app->redirect('/checkouts/' . $result->transaction->id);
    } else {
        $errorString = "";

        foreach($result->errors->deepAll() AS $error) {
            $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
        }

        $_SESSION["errors"] = $errorString;
        $app->redirect('/checkouts');
    }
});

$app->get('/checkouts/:transaction_id', function ($transaction_id) use ($app, $gateway) {
    $transaction = $gateway->transaction()->find($transaction_id);

    $transactionSuccessStatuses = [
        Braintree\Transaction::AUTHORIZED,
        Braintree\Transaction::AUTHORIZING,
        Braintree\Transaction::SETTLED,
        Braintree\Transaction::SETTLING,
        Braintree\Transaction::SETTLEMENT_CONFIRMED,
        Braintree\Transaction::SETTLEMENT_PENDING,
        Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT
     ];

    if (in_array($transaction->status, $transactionSuccessStatuses)) {
        $header = "Sweet Success!";
        $icon = "success";
        $message = "Your test transaction has been successfully processed. See the Braintree API response and try again.";
    } else {
        $header = "Transaction Failed";
        $icon = "fail";
        $message = "Your test transaction has a status of " . $transaction->status . ". See the Braintree API response and try again.";
    }

    $app->render('checkouts/show.php', [
        'transaction' => $transaction,
        'header' => $header,
        'icon' => $icon,
        'message' => $message
    ]);
});

$app->run();

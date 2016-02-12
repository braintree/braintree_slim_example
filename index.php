<?php
require 'vendor/autoload.php';
require_once("includes/braintree_init.php");

$app = new \Slim\Slim();

$app->config([
    'templates.path' => 'templates',
]);

$app->get('/', function () use ($app) {
    $app->redirect('/checkouts');
});

$app->get('/checkouts', function () use ($app) {
    $app->render('checkouts/new.php', [
        'client_token' => Braintree\ClientToken::generate(),
    ]);
});

$app->post('/checkouts', function () use ($app) {
    $result = Braintree\Transaction::sale([
        "amount" => $app->request->post('amount'),
        "paymentMethodNonce" => $app->request->post('payment_method_nonce'),
    ]);

    if($result->success) {
        $app->redirect('/checkouts/' . $result->transaction->id);
    } elseif($result->transaction) {
        $error = "Transaction status - " . $result->transaction->status;

        $_SESSION["errors"] = $error;
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

$app->get('/checkouts/:transaction_id', function ($transaction_id) use ($app) {
    $app->render('checkouts/show.php', [
        'transaction' => Braintree\Transaction::find($transaction_id),
    ]);
});

$app->run();

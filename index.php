<?php
require 'vendor/autoload.php';
require_once("braintree_init.php");

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

Braintree\Configuration::environment(getenv('BT_ENVIRONMENT'));
Braintree\Configuration::merchantId(getenv('BT_MERCHANT_ID'));
Braintree\Configuration::publicKey(getenv('BT_PUBLIC_KEY'));
Braintree\Configuration::privateKey(getenv('BT_PRIVATE_KEY'));

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
    } else {
        $app->redirect('/checkouts');
    }
});

$app->get('/checkouts/:transaction_id', function ($transaction_id) use ($app) {
    $app->render('checkouts/show.php', [
        'transaction' => Braintree\Transaction::find($transaction_id),
    ]);
});

$app->run();

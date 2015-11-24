<?php
require 'vendor/autoload.php';

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

$app->run();

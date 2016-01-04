<?php
require 'vendor/autoload.php';
require_once("braintree_init.php");

$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '20 minutes',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'my_app',
    'secret' => getenv('APP_SECRET_KEY'),
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));
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
        $errors = array();

        foreach($result->errors->deepAll() AS $error) {
            array_push($errors, $error->code . "-" . $error->message);
        }

        $app->flash('errors', $errors);
        $app->redirect('/checkouts');
    }
});

$app->get('/checkouts/:transaction_id', function ($transaction_id) use ($app) {
    $app->render('checkouts/show.php', [
        'transaction' => Braintree\Transaction::find($transaction_id),
    ]);
});

$app->run();

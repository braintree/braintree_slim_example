<?php
require_once("braintree_init.php");

class IndexPageTest extends PHPUnit_Framework_TestCase
{
    function test_rootReturnsHttpRedirect()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "localhost:3000");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($curl);
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $httpLocation = curl_getinfo($curl, CURLINFO_REDIRECT_URL);
        curl_close($curl);
        $this->assertEquals($httpStatus, 302);
        $this->assertContains("/checkouts", $httpLocation);
    }

    function test_checkoutsReturnsHttpSuccess()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "localhost:3000/checkouts");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($curl);
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $this->assertEquals($httpStatus, 200);
    }

    function test_checkoutsHasClientTokenOnPage()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "localhost:3000/checkouts");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        $this->assertRegExp('/var client_token = ".*";/', $output);
    }

    function test_checkoutsHasFormOnPage()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "localhost:3000/checkouts");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        $this->assertRegExp('/<form method="post" id="checkout"/', $output);
        $this->assertRegExp('/<div id="payment-form"/', $output);
        $this->assertRegExp('/<input type="text" name="amount" id="amount"/', $output);
    }

    function test_checkoutsShowContainsTransactionInformation()
    {
        $result = Braintree\Transaction::sale([
            'amount' => 10,
            'paymentMethodNonce' => 'fake-valid-nonce'
        ]);
        $transaction = $result->transaction;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "localhost:3000/checkouts/" . $transaction->id);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        $this->assertEquals($httpStatus, 200);
        $this->assertContains($transaction->id, $output);
        $this->assertContains($transaction->type, $output);
        $this->assertContains($transaction->amount, $output);
        $this->assertContains($transaction->status, $output);
        $this->assertContains($transaction->creditCardDetails->bin, $output);
        $this->assertContains($transaction->creditCardDetails->last4, $output);
        $this->assertContains($transaction->creditCardDetails->cardType, $output);
        $this->assertContains($transaction->creditCardDetails->expirationDate, $output);
        $this->assertContains($transaction->creditCardDetails->customerLocation, $output);
    }
}

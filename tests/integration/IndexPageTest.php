<?php
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

}

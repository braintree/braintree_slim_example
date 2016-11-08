# Braintree Slim Example
[![Build Status](https://travis-ci.org/braintree/braintree_slim_example.svg?branch=master)](https://travis-ci.org/braintree/braintree_slim_example)

An example Braintree integration for PHP in the Slim framework.

## Setup Instructions

1. Install composer and project dependencies:

  ```sh
  curl -sS https://getcomposer.org/installer | php
  php composer.phar install
  ```

2. Copy the contents of `example.env` into a new file named `.env` and fill in your Braintree API credentials. Credentials can be found by navigating to Account > My User > View Authorizations in the Braintree Control Panel. Full instructions can be [found on our support site](https://articles.braintreepayments.com/control-panel/important-gateway-credentials#api-credentials).

3. Start server:

  ```sh
  php -S 0.0.0.0:3000
  ```

## Deploying to Heroku

You can deploy this app directly to Heroku to see the app live. Skip the setup instructions above and click the button below. This will walk you through getting this app up and running on Heroku in minutes.

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/braintree/braintree_slim_example&env[BT_ENVIRONMENT]=sandbox)

## Running tests

Integration tests make API calls to Braintree and require that you set up your Braintree credentials. You can run this project's integration tests by adding your sandbox API credentials to `.env` and calling `vendor/bin/phpunit` on the command line.

## Testing Transactions

Sandbox transactions must be made with [sample credit card numbers](https://developers.braintreepayments.com/reference/general/testing/php#credit-card-numbers), and the response of a `Braintree_Transaction::sale()` call is dependent on the [amount of the transaction](https://developers.braintreepayments.com/reference/general/testing/php#test-amounts).

## Pro Tips

- The `example.env` contains an `APP_SECRET_KEY` setting. Even in development you should [generate your own custom secret key for your app](http://docs.slimframework.com/sessions/cookies/).

## Help

 * Found a bug? Have a suggestion for improvement? Want to tell us we're awesome? [Submit an issue](https://github.com/braintree/braintree_slim_example/issues)
 * Trouble with your integration? Contact [Braintree Support](https://support.braintreepayments.com/) / support@braintreepayments.com
 * Want to contribute? [Submit a pull request](https://help.github.com/articles/creating-a-pull-request)

## Disclaimer

This code is provided as is and is only intended to be used for illustration purposes. This code is not production-ready and is not meant to be used in a production environment. This repository is to be used as a tool to help merchants learn how to integrate with Braintree. Any use of this repository or any of its code in a production environment is highly discouraged.

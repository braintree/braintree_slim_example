# Braintree Slim Example
An example Braintree integration for PHP in the Slim framework.

## Setup Instructions

1. Install composer and project dependencies:

 `curl -sS https://getcomposer.org/installer | php`

 `php composer.phar install`

2. Copy the `example.env` file to `.env` and fill in your Braintree API credentials. Credentials can be found by navigating to Account > My user > View API Keys in the Braintree control panel. Full instructions can be [found on our support site](https://articles.braintreepayments.com/control-panel/important-gateway-credentials#api-credentials).

3. Start server
  `php -S 0.0.0.0:3000`

## Running tests

Integration tests make API calls to Braintree and require that you set up your Braintree credentials. You can run this project's integration tests by adding your sandbox API credentials to `.env` and calling `vendor/bin/phpunit` on the command line.

## Pro Tips

- The `example.env` contains an `APP_SECRET_KEY` setting. Even in development you should [generate your own custom secret key for your app](http://docs.slimframework.com/sessions/cookies/).

## Help

 * Found a bug? Hava a suggestion for improvement? Want to tell us we're awesome? [Submit an issue](https://github.com/braintree/braintree_rails_example/issues)
 * Trouble with your integration? Contact [Braintree Support](https://support.braintreepayments.com/) / support@braintreepayments.com
 * Want to contribute? [Submit a pull request](https://help.github.com/articles/creating-a-pull-request)

## Disclaimer

This code is provided as is and is only intended to be used for illustration purposes. This code is not production-ready and is not meant to be used in a production environment. This repository is to be used as a tool to help merchants learn how to integrate with Braintree. Any use of this repository or any of its code in a production environment is highly discouraged.

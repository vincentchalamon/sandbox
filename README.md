Sandbox for VinceCms
====================

<!--[![Build Status](https://secure.travis-ci.org/FriendsOfSymfony/FOSUserBundle.png?branch=master)](http://travis-ci.org/FriendsOfSymfony/FOSUserBundle)-->
[![Total Downloads](https://poser.pugx.org/vince/sandbox/downloads.png)](https://packagist.org/packages/vince/sandbox)
[![Latest Stable Version](https://poser.pugx.org/vince/sandbox/v/stable.png)](https://packagist.org/packages/vince/sandbox)

## Installation

Install [Composer](http://getcomposer.org/) and run the following command:
```shell
php composer.phar create-project vince/sandbox path/ -s dev
```

## Configuration

Run the following command to launch elasticsearch:
```shell
vendor/elasticsearch/binaries/bin/elasticsearch -f
```

Once you've created your database, run the following commands to build database with default fixtures:
```shell
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load -n
```

## Deploy

This sandbox has already been capified for 2 env : _dev_ (default) & _prod_. Update configuration file:
```ruby
# File: app/config/deploy.rb
...
set :domain, "1.2.3.4"
set :user,   "user"
```

Note : it's not recommended to set user password, prefer use [RSA key](http://www.caxy.com/blog/2008/04/getting-authorized_keys-to-work-logging-in-without-a-password-in-linux/).

You can now deploy through the following commands:
```shell
cap dev deploy:setup
cap dev deploy
```

## Contribute

If you contribute to this project, I advise you to catch emails through [mailcatcher](http://mailcatcher.me/).
You need to update your parameters.yml file as following:
```yml
parameters:
    ...
    mailer_host: 127.0.0.1:1025
```

## Todo

* Create admin for Block & User
* Documentation:
    * Installation:
        * Install sandbox with composer
        * Run elasticsearch
        * Load fixtures
    * Configuration:
        * Contact: noreply, recipient
        * Entities:
            * Ready for use
            * Adding properties & associations
    * Deployment:
        * Capifony
    * Developers:
        * Mailcatcher
* Enable Cloudflare for blog
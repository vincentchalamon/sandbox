Installation
============

Install [Composer](http://getcomposer.org/) and run the following command:
```shell
php composer.phar create-project vince/sandbox path/ -s dev
```

Configuration
=============

Run the following command to launch elasticsearch:
```shell
elasticsearch/bin/elasticsearch -f
```

Once you've created your database, run the following commands to build database with default fixtures:
```shell
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load -n
```

Deploy
======

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

Dev
===

If you contribute to this project, I advise you to catch emails through [mailcatcher](http://mailcatcher.me/).
You need to update your parameters.yml file as following:
```yml
parameters:
    ...
    mailer_host: 127.0.0.1:1025
```


Todo
====

* Migrate blog to Kimsufi/VPS
* Enable Cloudflare for blog
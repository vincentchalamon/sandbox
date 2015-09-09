**THIS PROJECT IS NOT MAINTAINED ANYMORE !**

Sandbox for VinceCms
====================

[![Total Downloads](https://poser.pugx.org/vince/sandbox/downloads.png)](https://packagist.org/packages/vince/sandbox)
[![Latest Stable Version](https://poser.pugx.org/vince/sandbox/v/stable.png)](https://packagist.org/packages/vince/sandbox)
[![Build Status](https://travis-ci.org/vincentchalamon/sandbox.png)](https://travis-ci.org/vincentchalamon/sandbox)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/cf774cad-f430-4aad-8e4f-db977bd839c8/mini.png)](https://insight.sensiolabs.com/projects/cf774cad-f430-4aad-8e4f-db977bd839c8)
[![Coverage Status](https://coveralls.io/repos/vincentchalamon/sandbox/badge.png)](https://coveralls.io/r/vincentchalamon/sandbox)

## Installation

Install [Composer](http://getcomposer.org/) and run the following command:
```shell
php composer.phar create-project vince/sandbox path/ -s dev
```

## Configuration

Run the following command to launch ElasticSearch:
```shell
vendor/elasticsearch/binaries/bin/elasticsearch -f
```

Once you've created your database, run the following command to build database with default fixtures:
```shell
php app/console project:reset
```

This command also publish assets for different project environments.

You should index your articles to ElasticSearch:
```shell
php app/console fos:elastica:populate
```

## Deploy

This sandbox has already been capified. Before your first deploy, you must update configuration file:
```ruby
# File: app/config/deploy/development.rb
...
set :domain, "1.2.3.4"
set :user,   "user"
```

Note: it's not recommended to set user password, prefer use [RSA key](https://help.github.com/articles/generating-ssh-keys).

You can now configure your server through the following command:
```shell
cap production deploy:setup
```

Then deploy with the following command:
```shell
cap production deploy
```

On deploy, capifony will ask you which version you want to deploy, by default the last tag.

## Developers

For local use, you should catch emails through [mailcatcher](http://mailcatcher.me/). You need to update your
parameters.yml file as following:
```yml
parameters:
    ...
    mailer_host: localhost:1025
```

## todo-vince Sandbox

- [x] Initialization command
- [x] Google Analytics tracking code (bundle configuration)
- [x] Default theme
- [x] Mail theme
- [ ] Behat
- [ ] Update travis configuration
- [ ] Documentation (README + PHPDoc + GitHub pages)

Documentation
=============

### Installation

- [ ] Install with composer
<!-- [ ] Launch elastic search daemon-->
- [ ] Create database
- [ ] Run _php app/console project:reset_
- [ ] Access to admin (url, login, password)

### Configuration

- [ ] Update config.yml: domain, sitename, tracking_code, no_reply, contact

### How to

#### Developers

- [ ] Fixtures
    - [ ] Create fixtures in YML
    - [ ] Create templates (& areas)
    - [ ] Create articles (& contents & metas)
    - [ ] Create menus (=> article or url, parent, children)
    - [ ] Create blocks
- [ ] Deploy (Capifony)
- [ ] Inject objects in template (listeners)
- [ ] Process forms (processors)
- [ ] Advanced
    - [ ] Override entities
    - [ ] Override controllers
    - [ ] Override admin
    - [ ] Catch mail on dev (MailCatcher)
    - [ ] PHPDoc

#### Designers

- [ ] Create template
    - [ ] Create twig file
    - [ ] Twig helpers
        - [ ] `vince` configuration
        - [ ] render_metas
        - [ ] render_meta
        - [ ] render_menu
        - [ ] render_block
        - [ ] localizeddate
- [ ] Assetic
    - [ ] Bootstrap
    - [ ] Ladda
    - [ ] Autosize
    - [ ] YUI compressor

Sandbox for VinceCms
====================

[![Build Status](https://travis-ci.org/vincentchalamon/sandbox.png)](https://travis-ci.org/vincentchalamon/sandbox)
[![Total Downloads](https://poser.pugx.org/vince/sandbox/downloads.png)](https://packagist.org/packages/vince/sandbox)
[![Latest Stable Version](https://poser.pugx.org/vince/sandbox/v/stable.png)](https://packagist.org/packages/vince/sandbox)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/cf774cad-f430-4aad-8e4f-db977bd839c8/mini.png)](https://insight.sensiolabs.com/projects/cf774cad-f430-4aad-8e4f-db977bd839c8)

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
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load -n
```

If you have installed elasticsearch, you sould index your articles to it:
```shell
php app/console fos:elastica:populate
```

## Deploy

This sandbox has already been capified. Before your first deploy, you must update configuration file:
```ruby
# File: app/config/deploy/production.rb
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

## Todo

* Search:
    * Configuration
    * Pager: KnpPaginatorBundle + ajax
* Themes
    * Default theme
    * Documentation theme: http://mojotech.github.io/stickymojo/
* Check each theme responsive (mail include)
* Documentation:
    * Install sandbox:
        * Download with composer
        * Launch elastic search
        * Create database
        * Load fixtures
    * Configure sandbox:
        * Contact: no-reply, recipient
        * Analytics:
            * Sitemap url
            * Configure Google Analytics tracking code
* How to:
    * For developers:
        * Catch mail on dev (Mailcatcher)
        * Deploy
        * Override entities
        * Override controller
        * Inject object in template (loader)
        * Create forms:
            * Types (link to VinceTypeBundle documentation)
            * Processor
        * Create fixtures:
            * Fixtures in YAML
            * Create Template & Areas (link to designer documentation)
            * Create Article:
                * Link Contents through Template areas
                * Add Metas
            * Menus:
                * Article or url
                * Tree (parent, children)
        * PHPDoc
    * For designers:
        * Create theme
            * Create template:
                * Create twig file
                * Register template & its areas in fixtures (link to developer documentation)
            * Assetic:
                * Bootstrap installed with Less
                * YUI compressor installer
            * Twig helpers:
                * render_menu
                * render_block
                * render_metas
                * localizeddate

## Nice to have

* Cache:
    * Doctrine
    * HTTP
    * APC
* Newsletter

## Type

* jQuery Chosen (http://davidwalsh.name/jquery-chosen)
* Switch (http://www.inserthtml.com/demos/css/radio-buttons/)
* FileUpload (http://blueimp.github.io/jQuery-File-Upload/basic.html)
* Dropzone (http://www.dropzonejs.com/)
* Autocomplete (http://jqueryui.com/autocomplete/)
* jQuery image cropper (http://tympanus.net/codrops/2009/11/04/jquery-image-cropper-with-uploader-v1-1/)
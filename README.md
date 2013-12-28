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
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load -n
```

You sould index your articles to elasticsearch:
```shell
php app/console fos:elastica:populate
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

## Developers

For local use, you should catch emails through [mailcatcher](http://mailcatcher.me/).
You need to update your parameters.yml file as following:
```yml
parameters:
    ...
    mailer_host: localhost:1025
```

## Todo

* Search:
    * Configuration
    * Pager: KnpPaginatorBundle + ajax
* Tests: https://travis-ci.org/
* Google Analytics tracking code
* Add compulsory metas on Article create
* Install Bootstrap + Less + YUI Compressor (update composer.json with hooks)
* Twig helper `render_metas`
* Admin
    * Article
    * Menu
    * Block
    * User
* Themes
    * Default theme
    * Admin theme
    * Documentation theme: http://mojotech.github.io/stickymojo/
    * Mail theme: http://templates.indextwo.com/e-mail/elegance/
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

* Image loader: http://luis-almeida.github.io/unveil/
* Image resizer: LiipImageBundle
* Cache
* Newsletter


## Type

* jQuery Chosen (http://davidwalsh.name/jquery-chosen)
* Switch (http://www.inserthtml.com/demos/css/radio-buttons/)
* Autosize (https://github.com/jackmoore/autosize)
* FileUpload (http://blueimp.github.io/jQuery-File-Upload/basic.html)
* TokenInput (http://loopj.com/jquery-tokeninput/)
* Dropzone (http://www.dropzonejs.com/)
* Autocomplete (http://jqueryui.com/autocomplete/)
* jQuery image cropper (http://tympanus.net/codrops/2009/11/04/jquery-image-cropper-with-uploader-v1-1/)


## Blog

* Devis
    * Theme
    * Admin
    * PDF generator: KnpSnappyBundle
* Comments:
    * Front: Disqus
    * Admin: count (link to Disqus)
* Social: http://www.hongkiat.com/blog/optimizing-social-button/
* Admin article (realisation) (ajax screen-shot: http://html2canvas.hertzen.com/screenshots.html): KnpSnappyBundle ?
* Submit buttons: http://msurguy.github.io/ladda-bootstrap/
* Enable CloudFlare
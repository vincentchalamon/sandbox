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

* Templates:
    X Mail: http://templates.indextwo.com/e-mail/elegance/
    X Devis
    * Default
    * Documentation: http://mojotech.github.io/stickymojo/
    * Admin
* Admin:
    * Article
    * Menu
    * Block
    * User
    * Devis
* Search:
    * Configuration
    * Tests
    * Pager: KnpPaginatorBundle + ajax
* Blog:
    * Comments:
        * Front: Disqus
        * Admin: count (link to Disqus)
    * Images:
        * Resize: LiipImageBundle
        * Loader: http://luis-almeida.github.io/unveil/
    * Social: http://www.hongkiat.com/blog/optimizing-social-button/
    * Admin:
        * Article (realisation) (ajax screen-shot: http://html2canvas.hertzen.com/screenshots.html): KnpSnappyBundle ?
        X Devis PDF generator: KnpSnappyBundle
* Submit buttons: http://msurguy.github.io/ladda-bootstrap/
* Unit/functional tests: https://travis-ci.org/
* Enable CloudFlare for blog
* Cache CMS
* Types:
    * jQuery Chosen (http://davidwalsh.name/jquery-chosen)
    * Switch (http://www.inserthtml.com/demos/css/radio-buttons/)
    * Autosize (https://github.com/jackmoore/autosize)
    * FileUpload (http://blueimp.github.io/jQuery-File-Upload/basic.html)
    * TokenInput (http://loopj.com/jquery-tokeninput/)
    * Dropzone (http://www.dropzonejs.com/)
    * Autocomplete (http://jqueryui.com/autocomplete/)
    * jQuery image cropper (http://tympanus.net/codrops/2009/11/04/jquery-image-cropper-with-uploader-v1-1/)
* Documentation:
    * Installation:
        * Install sandbox with composer
        * Run elasticSearch
        * Load fixtures
    * Configuration:
        * Contact: no-reply, recipient
        * Entities:
            * Ready for use
            * Adding properties & associations
    * Deployment:
        * Capifony
    * Developers:
        * MailCatcher
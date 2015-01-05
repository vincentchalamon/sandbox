Sandbox for VinceCms
====================

[![Build Status](https://travis-ci.org/vincentchalamon/sandbox.png)](https://travis-ci.org/vincentchalamon/sandbox)
[![Total Downloads](https://poser.pugx.org/vince/sandbox/downloads.png)](https://packagist.org/packages/vince/sandbox)
[![Latest Stable Version](https://poser.pugx.org/vince/sandbox/v/stable.png)](https://packagist.org/packages/vince/sandbox)
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

## todo-vince VinceCmsBundle

- [ ] I18n
- [ ] Cache APC
- [ ] Cache doctrine
- [ ] Cache HTTP
- [ ] Documentation (README + PHPDoc + GitHub pages)
- [ ] Search configuration (Symfony + ElasticSearch)
- [ ] Search pager: KnpPaginatorBundle
- [ ] Search ajax pager

Search
======

* Ne dois pas remonter :
    * Système : homepage, accueil, search, rechercher
    * Non publié : vincent
    * Pré publié : jordan
    * Pré publié temp : samuel
    * Dépublié : franck
* Doit remonter :
    * Publié : yannick
    * Publié aujourd'hui : benoit
    * Publié jusqu'à aujourd'hui : gilles
    * Publié temporairement : adrien

Documentation
=============

### Installation

- [ ] Install bundle with composer
- [ ] Update AppKernel
<!-- [ ] Install ElasticSearch with composer-->
<!-- [ ] Launch ElasticSearch-->

### Configuration

- [ ] Create override bundle (MyCmsBundle)
- [ ] Create override entities: Article, ArticleMeta, Block, Content, Menu
- [ ] Update config.yml: domain, sitename, tracking_code, model, no_reply, contact

### Fixtures

- [ ] Create fixtures in YML
- [ ] Create templates
- [ ] Create articles
- [ ] Create menus
- [ ] Create blocks

### CMS injection

- [ ] Inject objects (& forms) in template (listeners)
- [ ] Process forms (processors)

### Advanced

- [ ] Override controllers
- [ ] Catch mail on dev (MailCatcher)
- [ ] PHPDoc

## todo-vince VinceCmsSonataAdminBundle

- [ ] Documentation
- [ ] Languages on article list

Documentation
=============

### Installation

- [ ] Install bundle with composer
- [ ] Update AppKernel

### Configuration

- [ ] Override admin

## todo-vince VinceTypeBundle

- [ ] Switch (http://www.inserthtml.com/demos/css/radio-buttons/)
- [ ] Autocomplete (https://github.com/bassjobsen/Bootstrap-3-Typeahead)
- [ ] FileUpload (http://blueimp.github.io/jQuery-File-Upload/basic.html)
- [ ] Dropzone (http://www.dropzonejs.com/):
    * Hérite du champ text
    * Au chargement : taille standard si vide, sinon taille agrandie
    * Animation d'agrandissement au drag'n'drop (si taille standard)
    * Animation de réduction à la suppression si vide
- [ ] jQuery image cropper (http://tympanus.net/codrops/2009/11/04/jquery-image-cropper-with-uploader-v1-1/)
- [ ] ColorPicker
- [ ] Documentation (README + PHPdoc + GitHub pages)


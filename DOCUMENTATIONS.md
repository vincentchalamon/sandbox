Sandbox
=======

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

VinceCmsBundle
==============

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

### Search

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

VinceCmsSonataAdminBundle
=========================

### Installation

- [ ] Install bundle with composer
- [ ] Update AppKernel

### Configuration

- [ ] Override admin

# ONGR Oxid connector Bundle

### (Oxid articles import to ElasticSearch)

https://github.com/ongr-io/OXIDConnectorBundle/blob/master/Resources/docs/index.rst

## Installation

```
$ composer require ongr/oxid-connector-bundle dev-master
```

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        // Connections bundle must be installed !
		new ONGR\OXIDConnectorBundle\ONGROXIDConnectorBundle(),
    ];
}
```
## Chapter objetives

* Create Oxid articles import to ElasticSearch.

* **Assume that symfony and oxid uses same database.**

* Use new bundle for oxid import.


## Configuration

For Oxid articles import we will create OxidBundle:

```
$ app/console generate:bundle
//name OxidBundle
```

In OxidBundle we need to extend Document and Entities provided in OXIDConnectorBundle.

### Create Articles document.

For Articles import we need to create Articles Document.

Articles.php (src/OxidBundle/Document/)
```php
<?php

namespace OxidBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;
use ONGR\OXIDConnectorBundle\Document\ProductDocument as ParentDocument;

/**
 * @ES\Document(type="articles")
 */
class Articles extends ParentDocument
{
}
```
### Create entities.

For Articles import we need to extend these entities: 

* Article, 
* ArticleExtension,
* ArticleToAttribute
* ArticleToCategory
* Attribute
* Category
* CategoryToAttribute
* Manufacturer
* Seo
* Vendor

Article.php (src/OxidBundle/Entity/) Example:
```php
<?php

namespace OxidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ONGR\OXIDConnectorBundle\Entity\Article as ParentArticle;

/**
 * A class to test ONGR\OXIDConnectorBundle\Entity\Article abstract class.
 *
 * @ORM\Entity
 * @ORM\Table(name="oxarticles")
 */
class Article extends ParentArticle
{
}
```
Basically just copy files from `vendor/ongr/oxid-connector-bundle/Tests/Functional/Entity/` and replace the namespace inside.

config.yml

```
ongr_elasticsearch:
    managers:
        default:
            # ...
        oxid:
            connection: default
            debug: "%kernel.debug%"
            mappings:
                - ONGROXIDConnectorBundle
                - OxidBundle

doctrine:
    orm:
	   # already exist in config
       # auto_generate_proxy_classes: "%kernel.debug%"
       # auto_mapping: true
        mappings:
            ONGROXIDConnectorBundle:
                type: annotation
                alias: OXIDConnectorBundle
                dir: Entity
                prefix: ONGR\OXIDConnectorBundle\Entity
                is_bundle: true
            OxidBundle:
                type: annotation
                alias: OxidBundle
                dir: Entity
                prefix: OxidBundle\Entity
                is_bundle: true

ongr_connections:
    active_shop: oxid
    shops:
        oxid:
            shop_id: 0
    sync:
        sync_storage:
            mysql:
                connection: default
                table_name: ongr_sync_storage

ongr_oxid:
    database_mapping:
        oxid:
            tags:
                @shop_tag: '_1'
                @lang_tag: ''
            shop_id: 0
            lang_id: 0
    entity_namespace: OxidBundle
```

OxidExtension.php (src/OxidBundle/DependencyInjection):

```php
class OxidExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
		//...
        $loader->load('parameters.yml');
    }
}
```

parameters.yml (src/OxidBundle/Resources/config/):

```
parameters:
    ongr_oxid.seo_finder_service.repository: OxidBundle:Seo
    ongr_demo.oxid.import.finish.class: ONGR\ConnectionsBundle\EventListener\ImportFinishEventListener
    ongr_demo.oxid.import.product.modifier.class: ONGR\OXIDConnectorBundle\Modifier\ProductModifier
    ongr_demo.oxid.import.product.doctrine_entity_type: OxidBundle:Article
    ongr_demo.oxid.import.product.elastic_document_type: OxidBundle:Articles
```

services.yml (src/OxidBundle/Resources/config/):

```
services:
    # Product.
    ongr_demo.oxid.import.product.source:
        class: %ongr_connections.import.source.class%
        parent: ongr_connections.import.source
        arguments:
            - @doctrine.orm.default_entity_manager
            - %ongr_demo.oxid.import.product.doctrine_entity_type%
            - @es.manager.oxid
            - %ongr_demo.oxid.import.product.elastic_document_type%
        tags:
            - { name: kernel.event_listener, event: ongr.pipeline.import.oxid.product.source, method: onSource }

    ongr_demo.oxid.import.product.modifier:
        class: %ongr_demo.oxid.import.product.modifier.class%
        arguments: [ "@ongr_oxid.attr_to_doc_service" ]
        calls:
           - [ setLanguageId, [%ongr_oxid.language_id%] ]
           - [ setSeoFinderService, [ @ongr_oxid.seo_finder_service ] ]
        tags:
            - { name: kernel.event_listener, event: ongr.pipeline.import.oxid.product.modify, method: onModify }

    ongr_demo.oxid.import.product.consumer:
        class: %ongr_connections.import.consumer.class%
        parent: ongr_connections.import.consumer
        arguments:
            - @es.manager.oxid
        tags:
            - { name: kernel.event_listener, event: ongr.pipeline.import.oxid.product.consume, method: onConsume }

    ongr_demo.oxid.import.finish:
        class: %ongr_demo.oxid.import.finish.class%
        parent: ongr_connections.import.finish
        arguments:
            - @es.manager.oxid
        tags:
            - { name: kernel.event_listener, event: ongr.pipeline.import.oxid.product.finish, method: onFinish }
```
routing.yml (src/OxidBundle/Resources/config/):
```
oxid_article:
    path:     /oxid/article/{id}
    defaults: { _controller: OxidBundle:Default:index }
```

DefaultController.php (src/OxidBundle/Controller/):

```php
<?php

namespace OxidBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($id)
    {
        $manager = $this->get('es.manager.oxid');
        $repository = $manager->getRepository('OxidBundle:Articles');
        $product = $repository->find($id);

        return $this->render('OxidBundle:Default:index.html.twig', array('id' => $id, 'product' => $product));
    }
}
```

index.html.twig (src/OxidBundle/Resources/views/Default/):

```html
{% extends 'base.html.twig' %}
{% block title %}Oxid article{% endblock %}
{% block body %}
    <div class="container">
        <h2>Oxid connection bundle test</h2>

        <h3>Product with id '{{ id }}' is:</h3>
        <ul class="list-group" style="list-style:none;">
            <li class="list-group-item">Title: {{ product.title }}</li>
            <li class="list-group-item">Description: {{ product.description }}</li>
            <li class="list-group-item">Price: {{ product.price }}</li>
        </ul>
    </div>
{% endblock %}
```

Shows product info by id.


## Usage

Import articles to ElasticSearch:

```
$ app/console ongr:import:full oxid.product
```

access product by ID via URL:
```
url/oxid/article/6b6099c305f591cb39d4314e9a823fc1
```

## sync [to do]

[Back to documentation](README.md)
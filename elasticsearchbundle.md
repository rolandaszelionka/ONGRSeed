# ONGR Elastic Search Bundle

https://ongr.readthedocs.org/en/latest/components/ElasticsearchBundle/index.html

## Installation

```
$ composer require ongr/elasticsearch-bundle "~0.1"
```

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new ONGR\ElasticsearchBundle\ONGRElasticsearchBundle(),
    ];
}
```

## Configuration

config.yml
```
ongr_elasticsearch:
    connections:
        default:
            hosts:
                - { host: 127.0.0.1:9200 }
            index_name: product
            settings:
                refresh_interval: -1
                number_of_replicas: 1
    managers:
        default:
            connection: default
            mappings:
                - AppBundle
```

### Create Database for ElasticSearch

Product.php (src/AppBundle/Document/)

```php
<?php

namespace AppBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;
use ONGR\ElasticsearchBundle\Document\AbstractDocument;

/**
 * @ES\Document(type="product")
 */
class Product extends AbstractDocument
{
    /**
     * @var integer
     *
     * @ES\Property(name="stock", type="integer")
     */
    public $stock;

    /**
     * @var string
     *
     * @ES\Property(name="flower", type="string")
     */
    public $flower;

    /**
     * @var string
     *
     * @ES\Property(name="place", type="string")
     */
    public $place;
}
```

### Add data to ElasticSearch database

items.json (src/AppBundle/Data/):

```json
[
  {"count":25,"date":"2015-04-08T14:46:21+0200"},
  {"_type":"product","_id":"1","_source":{"flower":"Amaryllis","place":"German", "stock": 5, "category_id":"1", "urls": [{"url": "german-amaryllis/", "key": "normal-name"}, {"url": "amaryllis-german/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"2","_source":{"flower":"Amaryllis","place":"England", "stock": 2, "category_id":"1", "urls": [{"url": "england-amaryllis/", "key": "normal-name"}, {"url": "amaryllis-england/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"3","_source":{"flower":"Amaryllis","place":"Greece", "stock": 6, "category_id":"2", "urls": [{"url": "greece-amaryllis/", "key": "normal-name"}, {"url": "amaryllis-greece/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"4","_source":{"flower":"Amaryllis","place":"Portugal", "stock": 15, "category_id":"3", "urls": [{"url": "portugal-amaryllis/", "key": "normal-name"}, {"url": "amaryllis-portugal/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"5","_source":{"flower":"Amaryllis","place":"French", "stock": 4, "category_id":"4", "urls": [{"url": "french-amaryllis/", "key": "normal-name"}, {"url": "amaryllis-french/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"6","_source":{"flower":"Clover","place":"German", "stock": 1, "category_id":"5", "urls": [{"url": "german-clover/", "key": "normal-name"}, {"url": "clover-german/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"7","_source":{"flower":"Clover","place":"England", "stock": 27, "category_id":"6", "urls": [{"url": "england-clover/", "key": "normal-name"}, {"url": "clover-england/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"8","_source":{"flower":"Clover","place":"Greece", "stock": 14, "category_id":"7", "urls": [{"url": "greece-clover/", "key": "normal-name"}, {"url": "clover-greece/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"9","_source":{"flower":"Clover","place":"Portugal", "stock": 9, "category_id":"7", "urls": [{"url": "portugal-clover/", "key": "normal-name"}, {"url": "clover-portugal/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"10","_source":{"flower":"Clover","place":"French", "stock": 11, "category_id":"6", "urls": [{"url": "french-clover/", "key": "normal-name"}, {"url": "clover-french/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"11","_source":{"flower":"Bluebell","place":"German", "stock": 1, "category_id":"5", "urls": [{"url": "german-bluebell/", "key": "normal-name"}, {"url": "bluebell-german/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"12","_source":{"flower":"Bluebell","place":"England", "stock": 0, "category_id":"4", "urls": [{"url": "england-bluebell/", "key": "normal-name"}, {"url": "bluebell-england/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"13","_source":{"flower":"Bluebell","place":"Greece", "stock": 4, "category_id":"2", "urls": [{"url": "greece-bluebell/", "key": "normal-name"}, {"url": "bluebell-greece/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"14","_source":{"flower":"Bluebell","place":"Portugal", "stock": 45, "category_id":"2", "urls": [{"url": "portugal-bluebell/", "key": "normal-name"}, {"url": "bluebell-portugal/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"15","_source":{"flower":"Bluebell","place":"French", "stock": 1, "category_id":"2", "urls": [{"url": "french-bluebell/", "key": "normal-name"}, {"url": "bluebell-french/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"16","_source":{"flower":"Iris","place":"German", "stock": 33, "category_id":"1", "urls": [{"url": "german-iris/", "key": "normal-name"}, {"url": "iris-german/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"17","_source":{"flower":"Iris","place":"England", "stock": 22, "category_id":"4", "urls": [{"url": "england-iris/", "key": "normal-name"}, {"url": "iris-england/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"18","_source":{"flower":"Iris","place":"Greece", "stock": 11, "category_id":"5", "urls": [{"url": "greece-iris/", "key": "normal-name"}, {"url": "iris-greece/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"19","_source":{"flower":"Iris","place":"Portugal", "stock": 5, "category_id":"6", "urls": [{"url": "portugal-iris/", "key": "normal-name"}, {"url": "iris-portugal/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"20","_source":{"flower":"Iris","place":"French", "stock": 20, "category_id":"6", "urls": [{"url": "french-iris/", "key": "normal-name"}, {"url": "iris-french/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"21","_source":{"flower":"Foxglove","place":"German", "stock": 14, "category_id":"3", "urls": [{"url": "german-foxglove/", "key": "normal-name"}, {"url": "foxglove-german/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"22","_source":{"flower":"Foxglove","place":"England", "stock": 13, "category_id":"3", "urls": [{"url": "england-foxglove/", "key": "normal-name"}, {"url": "foxglove-england/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"23","_source":{"flower":"Foxglove","place":"Greece", "stock": 2, "category_id":"4", "urls": [{"url": "greece-foxglove/", "key": "normal-name"}, {"url": "foxglove-greece/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"24","_source":{"flower":"Foxglove","place":"Portugal", "stock": 7, "category_id":"7", "urls": [{"url": "portugal-foxglove/", "key": "normal-name"}, {"url": "foxglove-portugal/", "key": "opposite-name"}]}},
  {"_type":"product","_id":"25","_source":{"flower":"Foxglove","place":"French", "stock": 8, "category_id":"1", "urls": [{"url": "french-foxglove/", "key": "normal-name"}, {"url": "foxglove-french/", "key": "opposite-name"}]}}
]
```

DefaultController.php (src/AppBundle/Controller/):

```php
<?php

namespace AppBundle\Controller;

use AppBundle\Document\Product;
use ONGR\ElasticsearchBundle\DSL\Aggregation\StatsAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\TermsAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\TopHitsAggregation;
use ONGR\ElasticsearchBundle\ORM\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        /** @var Manager $manager */
        $manager = $this->get('es.manager');
        $repository = $manager->getRepository('AppBundle:Product');
        
        $product1 = $repository->find(1);
        
         // find by term (works with numbers not strings)
        $product2 = $repository->findBy(['stock' => 5]);
        
        return $this->render('AppBundle:Default:index.html.twig',
            array(
                'product1' => $product1,
                'product2' => $product2
            )
        );
    }
}
```

index.html.twig (src/AppBundle/Resources/views/Default/):

```
{% extends 'base.html.twig' %}
{% block title %}Elastic{% endblock %}
{% block body %}
<div class="container">
    <h2><a href="{{ path('ongr_es_home') }}">Elastic Search Bundle test</a></h2>

    <h3>Product with id 1 is:</h3>
    {{ product1.flower }} {{ product1.place }} {{ product1.stock }}

    <h3>Products with stock 5:</h3>
    <ul class="list-group" style="list-style:none;">
    {% for choice in product2 %}
        <li class="list-group-item"> {{ choice.flower }} {{ choice.place }} {{ choice.stock }}</li>
    {% endfor %}
    </ul>

</div>
{% endblock %}
```

## Usage

Create new index (elastic database)

```
$ app/console ongr:es:index:create
```

Import data to ElasticSearch

```
$ app/console ongr:es:index:import --raw src/AppBundle/Data/items.json
```

You can now see first product here `localhost:9200/product/product/1`

ElasticSearch comparison to MySQL: index = Database, type = Table. 

In this example we created 25 Tables (all named product) inside one Database (named product).

[Back to documentation](README.md)

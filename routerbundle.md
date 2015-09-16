# ONGR Router bundle

https://ongr.readthedocs.org/en/latest/components/RouterBundle/index.html

## Installation

```
$ composer require "ongr/router-bundle" "~0.1"
```

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new ONGR\RouterBundle\ONGRRouterBundle(),
    ];
}
```

## Configuration

config.yml:

```
ongr_router:
    es_manager: default
    seo_routes:
        product:
            _route: ongr_product
            _controller: AppBundle:Product:document
            _default_route: ongr_product_show
            _id_param: documentId
```

routing.yml

```
# Meta route that is called by OngrRouterBundle.
ongr_product:
    pattern:  /productDocument/{document}/ # This pattern is ignored and required for compatibility with Symfony.
    defaults: { _controller: AppBundle:Product:document }

# Actual route for accessing by document id (not SEO).
ongr_product_show:
    pattern:  /product/{id}
    defaults: { _controller: AppBundle:Product:show }
    requirements:
        page:  \d+
```
 
ProductController.php (src/AppBundle/Controller/):

```php
<?php

namespace AppBundle\Controller;

use AppBundle\Document\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller
{

    public function documentAction(Product $document, $seoKey)
    {
        return $this->render('AppBundle:Product:index.html.twig', array('product' => $document));
    }

    public function showAction($id)
    {
        $product = $this->get('es.manager')->getRepository('AppBundle:Product')->find($id);

        return $this->render('AppBundle:Product:index.html.twig', array('product' => $product));
    }
}
```

product.php (src/AppBundle/Document/)


```php
use ONGR\RouterBundle\Document\SeoAwareTrait;
 //...
class Product extends AbstractDocument
{
    use SeoAwareTrait;
    //...
```

SeoAwareTrait will handle urls.

Also items.json must have URLs added.

items.json example:

```javascript
{
 "_type":"product",
  "_id":"1",
  "_source":{
    "flower":"Amaryllis",
    "place":"German", 
    "stock": 5, 
    "urls": [
      {"url": "german-amaryllis/", "key": "normal-name"}, 
      {"url": "amaryllis-german/", "key": "opposite-name"}
      ]
   }
},
```
index.html.twig (src/AppBundle/Resources/views/Product/):

```html
{% extends 'base.html.twig' %}
{% block title %}Product page{% endblock %}
{% block body %}
<div class="container">
    <h2>Product info</h2>
    {% if product %}
    <ul class="list-group" style="list-style:none;">
        <li class="list-group-item">{{ product.flower }} </li>
        <li class="list-group-item">{{ product.place }} </li>
        <li class="list-group-item">{{ product.stock }} </li>
    </ul>
    {% else %}
        No product found.
    {% endif %}
</div>
{% endblock %}
```


## Usage

Now product can have direct link to it in twig, example:

```html
<a href="{{ path('ongr_product', {'document': product, '_seo_key': 'normal-name'}) }}">{{ product.flower }}</a>
```

in current example the link will be:

 `url/german-amaryllis/`

Product also can be accessed via URL, example:

 `url/product/{id}`

[Back to documentation](README.md)
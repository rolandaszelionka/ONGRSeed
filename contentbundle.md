# ONGR Content bundle

https://github.com/ongr-io/ContentBundle/tree/master/Resources/doc

## Installation

```
$ composer require ongr/content-bundle "~1.0"
$ composer update
```

If some problems - add bundle directly to composer.json and update it.

## Configuration

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        // Router bundle is required!
        new ONGR\ContentBundle\ONGRContentBundle(),
    ];
}
```

config.yml (app/config/):

```
ongr_content:
    es:
        repositories:
            product: es.manager.default.product   #returns _type:product from 'product' document.
            content: es.manager.default.content   #returns _type:content from  'product'  document.
            category: es.manager.default.category
    category_root_id: rootid
```

We will use `content` and `category` indexes.

For content snippets we will use 'content' index and 'content' type

routing.yml (src/AppBundle/Resources/config/):

```
ongr_content_snippet:
    resource: "@ONGRContentBundle/Resources/config/routing.yml"
```

### Create Documents

Content.php (src/AppBundle/Document):

```php
<?php

namespace AppBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

use ONGR\ContentBundle\Document\AbstractContentDocument as ParentDocument;
/**
 * @ES\Document(type="content")
 */
class Content extends ParentDocument
{

}
```

Category.php (src/AppBundle/Document):

```php
<?php

namespace AppBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

use ONGR\ContentBundle\Document\AbstractCategoryDocument as ParentDocument;
/**
 * @ES\Document(type="category")
 */
class Category extends ParentDocument
{

}
```

### Add data to ElasticSearch database

content.json (src/AppBundle/Data/content.json):

```json
[
  {"count":2,"date":"2015-04-08T14:46:21+0200"},
  {"_type":"content","_id":"1","_source":{"slug":"who-we-are","title":"Who We Are", "content": "Elastic believes getting immediate, actionable insight from data matters. As the company behind the three open source projects — Elasticsearch, Logstash, and Kibana — designed to take data from any source and search, analyze, and visualize it in real time, Elastic is helping people make sense of data. From stock quotes to Twitter streams, Apache logs to WordPress blogs, our products are extending what's possible with data, delivering on the promise that good things come from connecting the dots.", "urls": [{"url": "who-we-are/", "key": "whoweare"}] }},
  {"_type":"content","_id":"2","_source":{"slug":"learn","title":"Learn Elasti Search", "content": "Whether you're a newcomer to Elastic or a seasoned veteran, we've got a multitude of resources — from webinars to blog posts, demos, step-by-step tutorials, forums, and more — to help you do great things with data and inspire you to stay curious.", "urls": [{"url": "learn/", "key": "learn"}] }},
]
```

### How to get type:'content' item in controller?

```php
$manager = $this->get('es.manager');
$repository = $manager->getRepository('AppBundle:Content');
$content = $repository->find(1);
```

## Usage

in twig template:
```
{{ snippet('who-we-are', 'AppBundle:Snippet:content.html.twig') }}
```
`who-we-are` is a slug. Item content is loaded from type: `content` and can be accessed in content twig file.

content.html.twig (src/AppBundle/Resources/views/Snippet/):

```
{% if document is not empty %}
    <h3>{{ document.title }}</h3>
    <p>{{ include(template_from_string(document.content)) }}</p>
{% endif %}
```

Create index

```
$ app/console ongr:es:index:create
```

Import data to index

```
$ app/console ongr:es:index:import --raw src/AppBundle/Data/content.json
```

## To do
```
    {{ getCategoryTree('AppBundle:Default:index.html.twig', 2, '1') }}
    {{ getContentsBySlugs(['pirmas']) }}
```

## Add content functionality to new controller, route, view.

config.yml (app/config/):

```
ongr_router:
    es_manager: default
    seo_routes:
        # ...
        content:
            _route: ongr_content
            _controller: AppBundle:Content:document
            _default_route: ongr_content_show
            _id_param: documentId
```

routing.yml (app/config/):

```
ongr_content:
    pattern:  /contentDocument/{document}/
    defaults: { _controller: AppBundle:Content:document }

ongr_content_show:
    pattern:  /content/{id}
    defaults: { _controller: AppBundle:Content:show }
    requirements:
        page:  \d+
```

ContentController.php (src/AppBundle/Controller/):

```php
<?php

namespace AppBundle\Controller;

use AppBundle\Document\Content;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContentController extends Controller
{

    public function documentAction(Content $document, $seoKey)
    {
        return $this->render('AppBundle:Content:index.html.twig', array('content' => $document));
    }

    public function showAction($id)
    {
        $content = $this->get('es.manager')->getRepository('AppBundle:Content')->find($id);

        return $this->render('AppBundle:Content:index.html.twig', array('content' => $content));
    }
}
```

index.html.twig (src/AppBundle/Resources/views/Content/):

```
{% extends 'base.html.twig' %}
{% block title %}Content page{% endblock %}
{% block body %}
<div class="container">
    <h2>Content info</h2>
    {% if content %}
        <h3>Title: {{ content.title }} </h3>
        <h4>Slug: {{ content.slug }}</h4>
        <p>Content: {{ include(template_from_string(content.content)) }}</p>
    {% else %}
        No product found.
    {% endif %}
</div>
{% endblock %}
```

### Usage

content.html.twig (src/AppBundle/Resources/views/Snippet/):

```
<a href="{{ url('ongr_content', {'document': document}) }}">Link to content</a>
```

content also can be accessed via URL, example:
`url/content/{id}`

```
<a href="{{ path('ongr_content_show', {'id':1}) }}">Link to 1st item in content type</a> <br/>
```

## Seperate indexes for documents

**Multiple indexes for documents causes a problem with router bundle configuration, because router supports only 1 manager.**

### Configuration
config.yml (app/config/):

```
ongr_elasticsearch:
    connections:
        # ...
        content:
           hosts:
               - { host: 127.0.0.1:9200 }
           index_name: content
           settings:
               refresh_interval: -1
               number_of_replicas: 1
        category:
           hosts:
               - { host: 127.0.0.1:9200 }
           index_name: category
           settings:
               refresh_interval: -1
               number_of_replicas: 1
    managers:
        # ...
        content:
            connection: content
            debug: "%kernel.debug%"
            mappings:
                - AppBundle
        category:
            connection: category
            debug: "%kernel.debug%"
            mappings:
                - AppBundle

ongr_content:
    es:
        repositories:
            product: es.manager.default.product   #returns _type:product from 'product' document.
            content: es.manager.content.content   #returns _type:content from  'content'  document.
            category: es.manager.category.category
    category_root_id: rootid
```

### Usage

Create index

```
$ app/console ongr:es:index:create --manager=content
$ app/console ongr:es:index:create --manager=category
```

Import data to index

```
$ app/console ongr:es:index:import --manager=content --raw src/AppBundle/Data/content.json
```

[Back to documentation](README.md)
# ONGR Content bundle

https://github.com/ongr-io/ContentBundle/tree/master/Resources/doc

## Installation

```
$ composer require ongr/content-bundle "0.4.*"
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

categories.json (src/AppBundle/Data/)

```json
[
  {"count":7,"date":"2014-10-27T14:46:21+0200"},
  {"_type":"category","_id":"1","_source":{"path":"c","id":"1","parent_id":"5","root_id":null,"left":4,"right":5,"sort":21,"active":"true","hidden":null,"title":"North America","breadcrumbs":null,"urls":[{"url":"north-america\/","key":"cat-key"}],"expired_url":[],"level":1,"url_lowercased":["north-america\/"]}},
  {"_type":"category","_id":"2","_source":{"path":"6","id":"2","parent_id":"rootid","root_id":null,"left":1,"right":2,"sort":10,"active":"true","hidden":null,"title":"Europe","breadcrumbs":null,"urls":[{"url":"europe\/","key":"cat-key"}],"expired_url":[],"level":1,"url_lowercased":["europe\/"]}},
  {"_type":"category","_id":"3","_source":{"path":"e","id":"3","parent_id":"rootid","root_id":null,"left":11,"right":12,"sort":40,"active":"true","hidden":null,"title":"Africa","breadcrumbs":null,"urls":[{"url":"africa\/","key":"cat-key"}],"expired_url":[],"level":1,"url_lowercased":["africa\/"]}},
  {"_type":"category","_id":"4","_source":{"path":"d","id":"4","parent_id":"rootid","root_id":null,"left":13,"right":14,"sort":50,"active":"true","hidden":null,"title":"Oceania","breadcrumbs":null,"urls":[{"url":"oceania\/","key":"cat-key"}],"expired_url":[],"level":1,"url_lowercased":["oceania\/"]}},
  {"_type":"category","_id":"5","_source":{"path":"c","id":"5","parent_id":"rootid","root_id":null,"left":3,"right":8,"sort":20,"active":"true","hidden":null,"title":"Americas","breadcrumbs":null,"urls":[{"url":"americas\/","key":"cat-key"}],"expired_url":[],"level":1,"url_lowercased":["americas\/"]}},
  {"_type":"category","_id":"6","_source":{"path":"d","id":"6","parent_id":"rootid","root_id":null,"left":9,"right":10,"sort":30,"active":"true","hidden":null,"title":"Asia","breadcrumbs":null,"urls":[{"url":"asia\/","key":"cat-key"}],"expired_url":[],"level":1,"url_lowercased":["asia\/"]}},
  {"_type":"category","_id":"7","_source":{"path":"c","id":"7","parent_id":"5","root_id":null,"left":6,"right":7,"sort":22,"active":"true","hidden":null,"title":"South America","breadcrumbs":null,"urls":[{"url":"south-america\/","key":"cat-key"}],"expired_url":[],"level":1,"url_lowercased":["south-america\/"]}}
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
$ app/console ongr:es:index:import --raw src/AppBundle/Data/category.json
```

## Add content functionality to controller, route, view.

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

    public function documentAction(Content $document)
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
<a href="{{ url('ongr_content', {'document': document}) }}">Link to content: {{ document.title }}</a>
```

Content will be accessed via router.

Content also can be accessed via URL, example:
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


## Add category functionality to controller, route, view.

config.yml
```
ongr_router:
	// ..
    seo_routes:
     	// ..
        category:
            _route: ongr_category
            _controller: AppBundle:Category:document
            _default_route: ongr_category
            _id_param: document
            
            
ongr_filter_manager:
    managers:
		// ..
        category:
            filters:
                - cat_list_pager
                - category
            repository: 'es.manager.default.product'                
    filters:
		// ..
        pager:
        	// ..
            cat_list_pager:
               request_field: 'page'
               count_per_page: 2
               max_pages: 10           
        document_field:
        	// ..
            category:
                request_field: 'document'
                field: category_id            
```

'Category' filter manager will be used for products pagination in category. 

routing.yml
```
ongr_category:
    pattern:  /categoryDocument/{document}/
    methods:  [GET]
    defaults:
        _controller: AppBundle:Category:document
        managerName: "category"

_ongr_category_tree:
    pattern:  /_proxy/tree/{theme}/{maxLevel}/{partialTree}/{selectedCategory}
    defaults:
        _controller: AppBundle:Category:categoryTree
        theme: "top"
        maxLevel: 2
        partialTree: "pt"
        selectedCategory: null
```

CategoryControler.php (src/AppBundle/Controller/)
```php
<?php

namespace AppBundle\Controller;

use ONGR\ElasticsearchBundle\Document\DocumentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for category pages.
 */
class CategoryController extends Controller
{
    /**
     * Show category page with passed document object from router.
     *
     * @param Request           $request
     * @param DocumentInterface $document
     *
     * @return Response
     */
    public function documentAction(Request $request, $document)
    {
        $productList = $this->get('ongr_filter_manager.category')->execute($request);
        return $this->render(
            $this->getCategoryTemplate($request),
            [
                'filter_manager' => $productList,
                'category' => $document,
            ]
        );
    }

    /**
     * Category tree action.
     *
     * @param string $theme
     * @param int    $maxLevel
     * @param string $partialTree
     * @param string $selectedCategory
     *
     * @return Response
     */
    public function categoryTreeAction($theme, $maxLevel, $partialTree, $selectedCategory)
    {
        return $this->render(
            'AppBundle:Category:tree.html.twig',
            [
                'theme' => $this->getCategoryTreeTemplate($theme),
                'max_level' => $maxLevel,
                'selected_category' => $selectedCategory,
                'from_category' => $partialTree == 'pt' ? null : $partialTree,
            ]
        );
    }

    /**
     * Returns category page template name.
     *
     * @param Request $request
     *
     * @return string
     */
    protected function getCategoryTemplate(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return 'AppBundle:Product:list.html.twig';
        }
        return 'AppBundle:Category:category.html.twig';
    }

    /**
     * Returns category tree template name.
     *
     * @param string $theme
     *
     * @return string
     */
    protected function getCategoryTreeTemplate($theme)
    {
        switch ($theme) {
            case 'breadcrumbs':
                return 'AppBundle:Category:breadcrumbs.html.twig';
            default:
                return 'AppBundle:Category:inc/topmenu.html.twig';
        }
    }
}
```

### Twig templates

tree.html.twig: (src/AppBundle/Resources/views/Category/)

```
{% if from_category == null %}
    {{ getCategoryTree(theme, max_level, selected_category) }}
{% else %}
    {{ getCategoryChildTree(theme, max_level, selected_category, from_category) }}
{% endif %}
```

list.html.twig: (src/AppBundle/Resources/views/Product/)

```
{% extends 'base.html.twig' %}

{% block body %}
    <div class="container">
        {% if filter_manager.result is defined %}
            <h3>Products: </h3>
            <ul class="list-group">
                {% for product in filter_manager.result %}
                    <li class="list-group-item"><a href="{{ path('ongr_product', {'document': product, '_seo_key': 'normal-name'}) }}">{{ product.flower }} {{ product.stock }}</a></li>
                {% endfor %}
            </ul>
        {% endif %}


        {% if filter_manager.filters.cat_list_pager is defined %}
            <br/>
            <h3>Pagination: </h3>
            {{ ongr_paginate(filter_manager.filters.cat_list_pager.getPager(), 'ongr_category', {'document':category}) }}
        {% endif %}
    </div>
{% endblock %}
```

category.html.twig: (src/AppBundle/Resources/views/Category/)

```
{% extends 'AppBundle:Product:list.html.twig' %}
{% block title %}Category products{% endblock %}
{% block topmenu %}
    {{ render(url('_ongr_category_tree', {theme : 'top', maxLevel: 2, selectedCategory : category.id}), {strategy: 'ssi'}) }}
{% endblock %}
```

topmenu.html.twig: (src/AppBundle/Resources/views/Category/inc/)

```
<div class="container">
    <ul class="nav navbar-nav">
        {% for category in categories %}
            {% if not category.hidden %}
                <li class="{% if category.current or category.expanded %} current{% endif %}">
                    <a href="{{ url("ongr_category", {'document' : category}) }}">{{ category.title }}</a>
                    {% if category.hasVisibleChildren %}
                        {{ renderCategoryTree(category.children,null,null,'AppBundle:Category:inc/topsubmenu.html.twig') }}
                    {% endif %}
                </li>
            {% endif %}
        {% endfor %}
    </ul>
</div>
```

topsubmenu.html.twig: (src/AppBundle/Resources/views/Category/inc/)

```
<ul style="list-style:none;padding:0;">
    {% for category in categories %}
        {% if not category.hidden %}
            <li class="{% if category.current or category.expanded %} current{% endif %}">
                <a href="{{ url("ongr_category", {'document' : category}) }}">{{ category.title }}</a>
            </li>
        {% endif %}
    {% endfor %}
</ul>
```

base.html.twig (app/Resources/views/)

```
{% block topmenu %}
	{{ render(url('_ongr_category_tree', {'theme' : 'top', 'maxLevel': 1})) }}
{% endblock %}
```

The logic looks like that:

 - Menu render works this way:
   - It renders CategoryController categoryTreeAction method where 'tree' template is rendered.
   - In 'tree' template 'topmenu' theme, max menu deep level, selected category is passed.
   - 'topmenu' template content is displaued.

 - If you press on menu item:
   - It renders CategoryController documentAction method where it gets filtered products (from filter manager) and 'Category' template is rendered.
   - 'Category' template extends product 'list' template. There products are displayed.
   - 'Category' template renders 2-level menu (overrides one in base.tpl).

So in our example in base template only 1st level menu is displayed.
Then if you enter category and it have subcategories -they are also displayed.
                    - 

## Add breadcrumbs 

routing.yml:

```
_ongr_breadcrumbs:
    pattern:  /_proxy/breadcrumbs/{theme}/{maxLevel}/{partialTree}/{selectedCategory}
    methods:  [GET]
    defaults:
        _controller: AppBundle:Category:categoryTree
        theme: "breadcrumbs"
        maxLevel: 20
        partialTree: "pt"
        selectedCategory: null
```

base.html.twig (app/Resources/views/)

```
{% block breadcrumbs %}{% endblock %}
```

category.html.twig: (src/AppBundle/Resources/views/Category/)

```
{% block breadcrumbs %}
<div class="container">
    <ul class="breadcrumb">
        {{ render(url('_ongr_breadcrumbs', {selectedCategory : category.id}), {strategy: 'ssi'}) }}
        <li class="active">{{ category.title | trans | raw }}</li>
    </ul>
</div>
{% endblock %}
```

breadcrumbs.html.twig: (src/AppBundle/Resources/views/Category/)

```
{% for category in categories %}
    {% if category.expanded and category.id != selected_category %}
        <li>
            <a href="{{ url("ongr_category", {'document' : category}) }}">{{ category.title }}</a>
        </li>
        {% if category.hasVisibleChildren and category.expanded %}
            {{ renderCategoryTree(category.children, selected_category) }}
        {% endif %}
    {% endif %}
{% endfor %}
```

## TO DO

Category.php document

- tell about different field names (and getters + setter)

- if fields are different, for example: left,active,is_hidden (in oxid its oxleft,oxactive,oxhidden) - then its neccessery to overide 'buildQuery method'. this is how to do it

- when pushing data to elastic over api, link ID must be the same as in body

- how to show 3 level subcats

[Back to documentation](README.md)
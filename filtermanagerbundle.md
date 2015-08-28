# ONGR Filter manager Bundle

https://ongr.readthedocs.org/en/latest/components/FilterManagerBundle/index.html

## Installation

```
$ composer require "ongr/filter-manager-bundle": "~0.5.7"
```

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new ONGR\FilterManagerBundle\ONGRFilterManagerBundle(),
    ];
}
```

## Configuration

config.yml
```
ongr_filter_manager:
    managers:
        default:
            filters:
                - sorting
                - list_pager
                - search
                - fuzzy_search
                - range_search
                - flowers
                - stocks
                - places
            repository: 'es.manager.default.product'
    filters:
        sort:
            sorting:
                request_field: 'sort'
                choices:
                    - { label: Name ascending, field: flower, default: true, order: asc }
                    - { label: Name descending, field: flower,  order: desc }
                    - { label: Stock descending, field: stock,  order: desc }
                    - { label: Stock ascending, field: stock,  order: asc }
        pager:
            list_pager:
                request_field: 'page'
                count_per_page: 5
                max_pages: 10
        match: #reikia butinai tikslios frazes
            search:
                request_field: 'search'
                field: flower
        fuzzy: #suranda su nepilnom frazem
            fuzzy_search:
                request_field: 'fuzzy_search'
                field: flower
#                fuzziness: 5 # The maximum edit distance.
#                max_expansions: # The maximum number of terms that the fuzzy query will expand to.
#                prefix_length: # The number of initial characters which will not be “fuzzified”

        range:
            range_search:
                request_field: 'range'
                field: stock
        multi_choice:
            flowers:
                request_field: 'flower'
                field: flower
        document_field:
            stocks:
                request_field: 'stock'
                field: stock
        choice:
            places:
                request_field: 'places'
                field: place
                sort:
                  type: _term # arba _count
                  order: asc
                  priorities: # isvardinta salis pasirinkimu sarase rodoma pirmiau
                     - german
```

routing.yml

```
ongr_filter_page:
    pattern: /list
    methods:  [GET]
    defaults:
        _controller: AppBundle:List:index
```

ListController.php (src/AppBundle/Controller/):

```php
<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ListController extends Controller
{
    public function indexAction(Request $request)
    {
        $results = $this->get('ongr_filter_manager.default')->execute($request);

        return $this->render(
            'AppBundle:List:index.html.twig',
            [
                'filter_manager' => $results,
            ]
        );
    }
}
```

## Usage




### Sort Filter

```html
{% set filters = filter_manager.filters %}
{% for choice in filters.sorting.choices %}
	<a href="{{ path('ongr_filter_page', choice.getUrlParameters()) }}">{{ choice.label }}</a>
{% endfor %}
```

Sorts Name and Stock Asc, Desc.

### Pager Filter

```html
{% set filters = filter_manager.filters %}
{{ ongr_paginate(filters.list_pager.getPager(), 'ongr_filter_page') }}
```

Adds pagination.

### Search Filter

```html
{% set filters = filter_manager.filters %}
<form method="get" action="{{ path('ongr_filter_page', filters.search.getUrlParameters()) }}">
	<input type="text" name="search">
	<button class="btn" type="submit">Search</button>
</form>
```
Search for correct word. F.e. `Iris`


### Fuzzy Search Filter

```html
{% set filters = filter_manager.filters %}
<form method="get" action="{{ path('ongr_filter_page', filters.fuzzy_search.getUrlParameters()) }}">
	<input type="text" name="fuzzy_search">
	<button class="btn" type="submit">Send</button>
</form>
```
Search for word. F.e. `Iri`

### Range Search Filter

```html
{% set filters = filter_manager.filters %}
<form method="get" action="{{ path('ongr_filter_page', filters.range_search.getUrlParameters()) }}">
	<input type="text" name="range">
	<button class="btn" type="submit">Send</button>
</form>
```
Search for stock range. F.e. `1;5` Searches between 1 and 5.

### document_field Search Filter

```html
{% set filters = filter_manager.filters %}
<form method="get" action="{{ path('ongr_filter_page', filters.stocks.getUrlParameters()) }}">
	<input type="text" name="stock">
	<button class="btn" type="submit">Search</button>
</form>
```
Search for specific stock. F.e. `1`

### Choice Filter

```html
{% set filters = filter_manager.filters %}
{% for choice in filters.places.choices %}
	<a href="{{ path('ongr_filter_page', choice.getUrlParameters()) }}">{{ choice.label }}</a>
{% endfor %}
```

### Multi Choice Filter
```html
{% set filters = filter_manager.filters %}
{% for choice in filters.flowers.choices %}
	<a href="{{ path('ongr_filter_page', choice.getUrlParameters()) }}">{{ choice.label }}</a>
{% endfor %}
```

### All filters styled in 1 page
index.html.twig (src/AppBundle/Resources/views/List/):
```html
{% extends "::base.html.twig" %}
{% block title %}Filters{% endblock %}
{% block body %}
    <div class="container">

        <h2><a href="{{ path('ongr_filter_page') }}">Filters manager bundle test</a></h2>

        {% set filters = filter_manager.filters %}

        <div class="row">
            <div class="col-md-4">
                <h3><i>Sort</i> Flowers:</h3>

                <ul class="list-group" style="list-style:none;">
                    {% for choice in filters.sorting.choices %}
                        <li><a class="list-group-item {% if choice.active == true %}active{% endif %}" href="{{ path('ongr_filter_page', choice.getUrlParameters()) }}">{{ choice.label }}</a></li>
                    {% endfor %}
                </ul>
            </div>
            <div class="col-md-4">
                <h3>Place <i>Choice</i>:</h3>
                <ul class="list-group" style="list-style:none;">
                    {% for choice in filters.places.choices %}
                        <li><a  class="list-group-item {% if choice.active == true %}active{% endif %}" href="{{ path('ongr_filter_page', choice.getUrlParameters()) }}">{{ choice.label }}</a></li>
                    {% endfor %}
                </ul>
            </div>
            <div class="col-md-4">
                <h3><i>Multiselect</i> flowers:</h3>

                <ul class="list-group" style="list-style:none;">
                    {% for choice in filters.flowers.choices %}
                        <li><a  class="list-group-item {% if choice.active == true %}active{% endif %}" href="{{ path('ongr_filter_page', choice.getUrlParameters()) }}">{{ choice.label }}</a></li>
                    {% endfor %}
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <h3><i>Match Search</i> flower:</h3>

                <form method="get" action="{{ path('ongr_filter_page', filters.search.getUrlParameters()) }}">
                    <input type="text" name="search">
                    <button class="btn" type="submit">Search</button>
                </form>
                f.e iris
            </div>

            <div class="col-md-3">
                <h3><i>Fuzzy Search</i> flower:</h3>
                <form method="get" action="{{ path('ongr_filter_page', filters.fuzzy_search.getUrlParameters()) }}">
                    <input type="text" name="fuzzy_search">
                    <button class="btn" type="submit">Send</button>
                </form>
                f.e iri
            </div>

            <div class="col-md-3">
                <h3><i>Range Search</i> stock:</h3>
                <form method="get" action="{{ path('ongr_filter_page', filters.range_search.getUrlParameters()) }}">
                    <input type="text" name="range">
                    <button class="btn" type="submit">Send</button>
                </form>
                f.e 1;5
            </div>

            <div class="col-md-3">
                <h3><i>doc_field Search</i> stock:</h3>

                <form method="get" action="{{ path('ongr_filter_page', filters.stocks.getUrlParameters()) }}">
                    <input type="text" name="stock">
                    <button class="btn" type="submit">Search</button>
                </form>
                f.e 2
            </div>
        </div>

        <br/>
        <h3>Products: </h3>
        <ul class="list-group">
            {% for product in filter_manager.result %}
               {# this could be used when routing bundle is setuped #}
               {#<li class="list-group-item"><a href="{{ path('ongr_product', {'document': product, '_seo_key': 'normal-name'}) }}">{{ product.flower }} {{ product.stock }}</a></li>#}
               <li class="list-group-item">{{ product.flower }} {{ product.stock }}</li>
            {% endfor %}
        </ul>

        <br/>
        <h3>Pagination: </h3>
        {{ ongr_paginate(filters.list_pager.getPager(), 'ongr_filter_page') }}

    </div>
{% endblock %}
```

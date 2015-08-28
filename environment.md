# Environment Preparation

## Install

* [Download](https://getcomposer.org/download/) and install composer

* Install symfony (without AcmeDemoBundle)
```
$ composer create-project symfony/framework-standard-edition projectName
$ composer install
```
* [Download](https://www.elastic.co/downloads/elasticsearch) and run elasticsearch
```
$ bin/elasticsearch
$ sudo bin/plugin -install mobz/elasticsearch-head
```

## Prepare Bundle

base.html.twig (app/Resources/views/):
```html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>{% block title %}Welcome!{% endblock %}</title>
        {% block stylesheets %}{% endblock %}
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    </head>
    <body>
        {% block body %}{% endblock %}
        {% block javascripts %}{% endblock %}
    </body>
</html>
```


routing.yml (app/config):
```
app:
    resource: @AppBundle/Resources/config/routing.yml
```

Create routing in AppBundle 

routing.yml (src/AppBundle/Resources/config/):

```
home:
	path: /
	defaults: { _controller: AppBundle:Default:index }
```

DefaultController.php (src/AppBundle/Controllers/):

```php
<?php

namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	public function indexAction()
	{
		return $this->render('AppBundle:Default:index.html.twig');
	}
}
```    
 
index.html.twig (src/AppBundle/Resources/views/Default/):

``` 
{% extends "::base.html.twig" %}

{% block body %}
	homepage
{% endblock %}
``` 
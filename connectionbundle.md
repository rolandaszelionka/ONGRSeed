# ONGR Connections bundle

https://ongr.readthedocs.org/en/latest/components/ConnectionsBundle/import/import.html

## Installation 

composer.json:

```
"doctrine/orm": ">=2.5",
"doctrine/dbal": ">=2.5",
 //...
"ongr/connections-bundle": "~0.8.1"
```

```
$ composer update
```

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new ONGR\ConnectionsBundle\ONGRConnectionsBundle(),
    ];
}
```

## Full import from MySQL

Create Entity in src/AppBundle/Entity

Product.php:

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $flower;

    /**
     * @ORM\Column(type="string")
     */
    protected $place;

    /**
     * @ORM\Column(type="integer")
     */
    protected $stock;
```

Generate getters and setters.

Add it to DB:

```
$ app/console doctrine:schema:create 
```

Add data to product table:

```
INSERT INTO `product` (`id`, `flower`, `place`, `stock`) VALUES
(30, 'a', 'c', 1),
(31, 'b', 'c', 1);
```

### Configure Connection bundle for import.

services.yml (app/config/):

```
parameters:
    test.import.modifier.class: AppBundle\EventListener\ImportModifyEventListener

services:
    test.import.source:
           class: %ongr_connections.import.source.class%
           parent: ongr_connections.import.source
           arguments:
               - @doctrine.orm.default_entity_manager
               - AppBundle:Product  # my.doctrine.entity.class
               - @es.manager
               - AppBundle:Product  # my.elasticsearch.entity.class
           tags:
               - { name: kernel.event_listener, event: ongr.pipeline.import.default.source, method: onSource }

    test.import.modifier:
       # parent: ongr_connections.import.modifier
       # here instead of taking 'parent' we need our modifyListener for our db table structure
        class: %test.import.modifier.class%
        tags:
            - { name: kernel.event_listener, event: ongr.pipeline.import.default.modify, method: onModify }

    test.import.consumer:
        class: %ongr_connections.import.consumer.class%
        parent: ongr_connections.import.consumer
        arguments:
            - @es.manager
        tags:
            - { name: kernel.event_listener, event: ongr.pipeline.import.default.consume, method: onConsume }

    test.import.finish:
        class: %ongr_connections.import.finish.class%
        parent: ongr_connections.import.finish
        arguments:
            - @es.manager
        tags:
            - { name: kernel.event_listener, event: ongr.pipeline.import.default.finish, method: onFinish }
```

Here basically everything is taken from Connection bundle. In import.modifier our event listener need to be used, because of our DB table structure.

Create event listener in src/AppBundle/EventListener/

ImportModifyEventListener.php

```php
<?php

namespace AppBundle\EventListener;

use ONGR\ConnectionsBundle\EventListener\AbstractImportModifyEventListener;
use ONGR\ConnectionsBundle\Pipeline\Event\ItemPipelineEvent;
use ONGR\ConnectionsBundle\Pipeline\Item\AbstractImportItem;


class ImportModifyEventListener extends AbstractImportModifyEventListener
{

    /**
     * Assigns data in entity to relevant fields in document.
     *
     * @param AbstractImportItem $eventItem
     * @param ItemPipelineEvent $event
     */
    protected function modify(AbstractImportItem $eventItem, ItemPipelineEvent $event)
    {
        /** @var Product $data */
        $data = $eventItem->getEntity();
        /** @var Product $document */
        $document = $eventItem->getDocument();

        $document->setId($data->getId());
        $document->setFlower($data->getFlower());
        $document->setPlace($data->getPlace());
        $document->setStock($data->getStock());
    }
}
```

To use setId and getId documents must have getters and setters.

### Import db table to elastic:

```
$ app/console ongr:import:full
```

-----


# How to move services.yml from app/ to Bundle

Create DependencyInjection folder in src/AppBundle/ and add AppExtension.php file to it.

AppExtension.php:

```php
<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AppExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
```
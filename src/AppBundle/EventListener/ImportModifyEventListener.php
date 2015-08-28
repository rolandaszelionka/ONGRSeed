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
<?php

namespace AppBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;
use ONGR\ElasticsearchBundle\Document\AbstractDocument;
use ONGR\RouterBundle\Document\SeoAwareTrait;

/**
 * @ES\Document(type="product")
 */
class Product extends AbstractDocument
{
    use SeoAwareTrait;

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

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return string
     */
    public function getFlower()
    {
        return $this->flower;
    }

    /**
     * @param string $flower
     */
    public function setFlower($flower)
    {
        $this->flower = $flower;
    }

    /**
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param string $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }


}
<?php

namespace OxidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ONGR\OXIDConnectorBundle\Entity\Manufacturer as ParentManufacturer;

/**
 * A class to test ONGR\OXIDConnectorBundle\Entity\Manufacturer abstract class.
 *
 * @ORM\Entity
 * @ORM\Table(name="oxmanufacturers")
 */
class Manufacturer extends ParentManufacturer
{
}

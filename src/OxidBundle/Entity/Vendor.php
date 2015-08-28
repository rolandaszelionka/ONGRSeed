<?php

namespace OxidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ONGR\OXIDConnectorBundle\Entity\Vendor as ParentVendor;

/**
 * A class to test ONGR\OXIDConnectorBundle\Entity\Vendor abstract class.
 *
 * @ORM\Entity
 * @ORM\Table(name="oxvendor")
 */
class Vendor extends ParentVendor
{
}

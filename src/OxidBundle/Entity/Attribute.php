<?php

namespace OxidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ONGR\OXIDConnectorBundle\Entity\Attribute as ParentAttribute;

/**
 * A class to test ONGR\OXIDConnectorBundle\Entity\Attribute abstract class.
 *
 * @ORM\Entity
 * @ORM\Table(name="oxattribute")
 */
class Attribute extends ParentAttribute
{
}

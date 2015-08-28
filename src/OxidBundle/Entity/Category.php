<?php

namespace OxidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ONGR\OXIDConnectorBundle\Entity\Category as ParentCategory;

/**
 * A class to test ONGR\OXIDConnectorBundle\Entity\Category abstract class.
 *
 * @ORM\Entity
 * @ORM\Table(name="oxcategories")
 */
class Category extends ParentCategory
{
}

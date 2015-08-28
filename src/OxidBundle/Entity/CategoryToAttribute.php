<?php

namespace OxidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ONGR\OXIDConnectorBundle\Entity\CategoryToAttribute as ParentCategoryToAttribute;

/**
 * A class to test ONGR\OXIDConnectorBundle\Entity\CategoryToAttribute abstract class.
 *
 * @ORM\Entity
 * @ORM\Table(name="oxcategory2attribute")
 */
class CategoryToAttribute extends ParentCategoryToAttribute
{
}

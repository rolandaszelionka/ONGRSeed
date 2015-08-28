<?php

namespace OxidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ONGR\OXIDConnectorBundle\Entity\ArticleToAttribute as ParentArticleToAttribute;

/**
 * A class to test ONGR\OXIDConnectorBundle\Entity\ArticleToAttribute abstract class.
 *
 * @ORM\Entity
 * @ORM\Table(name="oxobject2attribute")
 */
class ArticleToAttribute extends ParentArticleToAttribute
{
}

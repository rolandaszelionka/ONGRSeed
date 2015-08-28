<?php

namespace OxidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ONGR\OXIDConnectorBundle\Entity\ArticleToCategory as ParentArticleToCategory;

/**
 * A class to test ONGR\OXIDConnectorBundle\Entity\ArticleToAttribute abstract class.
 *
 * @ORM\Entity
 * @ORM\Table(name="oxobject2category")
 */
class ArticleToCategory extends ParentArticleToCategory
{
}

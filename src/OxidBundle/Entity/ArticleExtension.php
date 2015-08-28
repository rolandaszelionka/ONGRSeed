<?php

namespace OxidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ONGR\OXIDConnectorBundle\Entity\ArticleExtension as ParentArticleExtension;

/**
 * A class to test ONGR\OXIDConnectorBundle\Entity\ArticleExtension abstract class.
 *
 * @ORM\Entity
 * @ORM\Table(name="oxartextends")
 */
class ArticleExtension extends ParentArticleExtension
{
}

<?php

namespace OxidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ONGR\OXIDConnectorBundle\Entity\Article as ParentArticle;

/**
 * A class to test ONGR\OXIDConnectorBundle\Entity\Article abstract class.
 *
 * @ORM\Entity
 * @ORM\Table(name="oxarticles")
 */
class Article extends ParentArticle
{
}

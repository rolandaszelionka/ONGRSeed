<?php

namespace OxidBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ONGR\OXIDConnectorBundle\Entity\Seo as ParentSeo;

/**
 * A class to test ONGR\OXIDConnectorBundle\Entity\Seo abstract class.
 *
 * @ORM\Entity
 * @ORM\Table(name="oxseo")
 */
class Seo extends ParentSeo
{
}

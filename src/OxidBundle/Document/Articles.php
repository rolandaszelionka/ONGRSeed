<?php

namespace OxidBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;
use ONGR\OXIDConnectorBundle\Document\ProductDocument as ParentDocument;

/**
 * @ES\Document(type="articles")
 */
class Articles extends ParentDocument
{
}
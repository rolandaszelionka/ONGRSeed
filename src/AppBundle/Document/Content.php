<?php

namespace AppBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

use ONGR\ContentBundle\Document\AbstractContentDocument as ParentDocument;

/**
 * @ES\Document(type="content")
 */
class Content extends ParentDocument
{

}

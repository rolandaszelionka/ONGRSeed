<?php

namespace AppBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

use ONGR\ContentBundle\Document\AbstractCategoryDocument as ParentDocument;
/**
 * @ES\Document(type="category")
 */
class Category extends ParentDocument
{

}

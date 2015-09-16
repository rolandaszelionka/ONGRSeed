<?php

namespace AppBundle\Controller;

use AppBundle\Document\Content;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContentController extends Controller
{

    public function documentAction(Content $document)
    {
        return $this->render('AppBundle:Content:index.html.twig', array('content' => $document));
    }

    public function showAction($id)
    {
        $content = $this->get('es.manager')->getRepository('AppBundle:Content')->find($id);

        return $this->render('AppBundle:Content:index.html.twig', array('content' => $content));
    }
}
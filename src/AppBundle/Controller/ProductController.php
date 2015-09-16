<?php

namespace AppBundle\Controller;

use AppBundle\Document\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller
{

    public function documentAction(Product $document)
    {
        return $this->render('AppBundle:Product:index.html.twig', array('product' => $document));
    }

    public function showAction($id)
    {
        $product = $this->get('es.manager')->getRepository('AppBundle:Product')->find($id);

        return $this->render('AppBundle:Product:index.html.twig', array('product' => $product));
    }
}
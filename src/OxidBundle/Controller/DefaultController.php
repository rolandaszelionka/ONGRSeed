<?php

namespace OxidBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($id)
    {
        $manager = $this->get('es.manager.oxid');
        $repository = $manager->getRepository('OxidBundle:Articles');
        $product = $repository->find($id);

        return $this->render('OxidBundle:Default:index.html.twig', array('id' => $id, 'product' => $product));
    }
}

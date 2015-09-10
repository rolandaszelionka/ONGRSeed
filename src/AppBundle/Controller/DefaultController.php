<?php

namespace AppBundle\Controller;

use ONGR\ElasticsearchBundle\ORM\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * for elastic search bundle test
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        /** @var Manager $manager */
        $manager = $this->get('es.manager');
        $repository = $manager->getRepository('AppBundle:Product');

        // remove
//        $repository->remove(27);

        // add
//        $prod = new Product();
//        $prod->setId(27);
//        $prod->setFlower('Roze');
//        $prod->setPlace('Lithuania');
//        $prod->setStock(1);
//        $manager->persist($prod);   //adds to bulk container
//        $manager->commit();         //bulk data to index and flush

        // find
        $product1 = $repository->find(1);

        // find by term (works with numbers not strings)
        $product2 = $repository->findBy(['stock' => 5], []);

        // find by options: criteria, order, limit
        $product3 = $repository->findBy(['stock' => 1], ['flower' => 'asc'], 3);

        // Search in content type
        $contentRepository = $manager->getRepository('AppBundle:Content');
        $product4 = $contentRepository->find(1);

        return $this->render('AppBundle:Default:index.html.twig',
            array(
                'product1' => $product1,
                'product2' => $product2,
                'product3' => $product3,
                'product4' => $product4
            )
        );
    }
}

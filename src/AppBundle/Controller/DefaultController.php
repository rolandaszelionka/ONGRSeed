<?php

namespace AppBundle\Controller;

use ONGR\ElasticsearchBundle\DSL\Aggregation\StatsAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\TermsAggregation;
use ONGR\ElasticsearchBundle\DSL\Aggregation\TopHitsAggregation;
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
        $product2 = $repository->findBy(['stock' => 5]);

        // https://ongr.readthedocs.org/en/latest/components/ElasticsearchBundle/dsl/index.html

        // find results number and statistics (min,max)
        $statsAggregation = new StatsAggregation('grades_count');
        $statsAggregation->setField('stock');
        $search = $repository
            ->createSearch()
            ->addAggregation($statsAggregation);

        $results = $repository->execute($search);
        $totalCount = $results->getTotalCount();
        $statsResult = $results->getAggregations()->find('grades_count');
var_dump($totalCount);
var_dump($statsResult);

        // group by flower
        $termsAggregation = new TermsAggregation('groupby');
        $termsAggregation->setField('flower');
        $search = $repository
            ->createSearch()
            ->addAggregation($termsAggregation);
        $results = $repository->execute($search);
        $termsResults = $results->getAggregations()->find('groupby');

        // group by flower and return them
        $termsAggregation = new TermsAggregation('grades_count');
        $topHitsAggregation = new TopHitsAggregation('hits_count');
        $termsAggregation->setField('flower');
        $termsAggregation->addAggregation($topHitsAggregation);
        $search = $repository
            ->createSearch()
            ->addAggregation($termsAggregation);
        $results = $repository->execute($search);
var_dump($results->getAggregations()->find('grades_count'));
var_dump($results->getAggregations()->find('grades_count')[0]->getValue());
var_dump($results->getAggregations()->find('grades_count')[0]->getAggregations()['hits_count']);


        return $this->render('AppBundle:Default:index.html.twig',
            array(
                'product1' => $product1,
                'product2' => $product2
            )
        );
    }
}

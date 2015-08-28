<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ListController extends Controller
{
    /**
     * for filter manager bundle test
     */
    public function indexAction(Request $request)
    {
        $results = $this->get('ongr_filter_manager.default')->execute($request);

        return $this->render(
            'AppBundle:List:results.html.twig',
            [
                'filter_manager' => $results,
            ]
        );
    }
}
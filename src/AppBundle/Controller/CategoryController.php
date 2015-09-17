<?php

namespace AppBundle\Controller;

use ONGR\ElasticsearchBundle\Document\DocumentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for category pages.
 */
class CategoryController extends Controller
{
    /**
     * Show category page with passed document object from router.
     *
     * @param Request           $request
     * @param DocumentInterface $document
     *
     * @return Response
     */
    public function documentAction(Request $request, $document)
    {
        $productList = $this->get('ongr_filter_manager.category')->execute($request);
        return $this->render(
            $this->getCategoryTemplate($request),
            [
                'filter_manager' => $productList,
                'category' => $document,
            ]
        );
    }

    /**
     * Category tree action.
     *
     * @param string $theme
     * @param int    $maxLevel
     * @param string $partialTree
     * @param string $selectedCategory
     *
     * @return Response
     */
    public function categoryTreeAction($theme, $maxLevel, $partialTree, $selectedCategory)
    {
        return $this->render(
            'AppBundle:Category:tree.html.twig',
            [
                'theme' => $this->getCategoryTreeTemplate($theme),
                'max_level' => $maxLevel,
                'selected_category' => $selectedCategory,
                'from_category' => $partialTree == 'pt' ? null : $partialTree,
            ]
        );
    }

    /**
     * Returns category page template name.
     *
     * @param Request $request
     *
     * @return string
     */
    protected function getCategoryTemplate(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return 'AppBundle:Product:list.html.twig';
        }
        return 'AppBundle:Category:category.html.twig';
    }

    /**
     * Returns category tree template name.
     *
     * @param string $theme
     *
     * @return string
     */
    protected function getCategoryTreeTemplate($theme)
    {
        switch ($theme) {
            case 'breadcrumbs':
                return 'AppBundle:Category:breadcrumbs.html.twig';
            default:
                return 'AppBundle:Category:inc/topmenu.html.twig';
        }
    }
}

<?php


namespace App\Controller;


use App\View\HomepageView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    /**
     * Homepage - displays paginated posts and a map
     *
     * @param Request $request
     * @param HomepageView $view
     * @param string|null $filter_slug
     * @return Response
     */
    public function index(Request $request, HomepageView $view, ?string $filter_slug) {
        $showFavorite = $filter_slug !== null;

        if ($showFavorite) {
            $this->denyAccessUnlessGranted('ROLE_USER');
        }

        return $this->render('homepage.html.twig', $view->create($request, $showFavorite));
    }
}
<?php


namespace App\Controller;


use App\View\HomepageView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function index(HomepageView $view) {
        return $this->render('homepage.html.twig', $view->create());
    }
}
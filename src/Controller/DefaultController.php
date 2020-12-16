<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Granam\CzechVocative\CzechName;

class DefaultController extends AbstractController
{
    public function index() {
        $parameters = [];

        if ($this->getUser()) {
            $name = new CzechName();
            $vocative = $name->vocative($this->getUser()->getName());

            $parameters['name'] = $vocative;
        }

        return $this->render('homepage.html.twig', $parameters);
    }
}
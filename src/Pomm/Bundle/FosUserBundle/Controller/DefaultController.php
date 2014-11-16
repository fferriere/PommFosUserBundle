<?php

namespace Pomm\Bundle\FosUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PommFosUserBundle:Default:index.html.twig', array('name' => $name));
    }
}

<?php

namespace Vertacoo\LinksDirectoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('VertacooLinksDirectoryBundle:Default:index.html.twig');
    }
}

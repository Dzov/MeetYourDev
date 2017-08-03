<?php

namespace MYD\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HomeController extends Controller
{
  public function indexAction()
  {
    $securityContext = $this->container->get('security.authorization_checker');
    if ($securityContext->isGranted('ROLE_USER')) {
      return $this->redirect($this->generateUrl('myd_platform_home'));
    } else {
      return $this->render('::base.html.twig');
    }
  }
}
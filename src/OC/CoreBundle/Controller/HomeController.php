<?php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//requests
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('OCCoreBundle:Home:index.html.twig');
    }

    public function contactAction(Request $request)
	{
		//message flash et redirecton acceuil
		$request->getSession()->getFlashBag()->add('notice', 'La page contact n\'est pas encore disponible. Merci de revenir plus tard');

		return $this->redirect($this->generateUrl('oc_core_homepage'));
	}
}

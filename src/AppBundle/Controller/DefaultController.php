<?php

namespace AppBundle\Controller;

use AppBundle\Form\CustomerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index", requirements={"_locale": "en|uk"})
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(CustomerType::class);
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

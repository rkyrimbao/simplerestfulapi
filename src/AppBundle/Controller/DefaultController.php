<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        
        $jwt = new \Lindelius\JWT\JWT();

        $jwt->exp = time() + (60 * 60 * 2); // expire after 2 hours
        $jwt->iat = time();
        $jwt->sub = $this->container->getParameter('api_keys')[0];

        $accessToken = $jwt->encode($this->container->getParameter('api_keys')[0]);
        
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR, 'token' => $accessToken
        ]);
    }
}
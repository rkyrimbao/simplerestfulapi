<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\FootballLeague;

class ApiController extends Controller
{
    /**
     * @Route("/league")
     */
    public function indexAction(Request $request)
    {	
    	$leagues = $this
    		->getRepository(FootballLeague::class)
    		->findAll();

    	$data = array();
    	foreach ($leagues as $league) {
    		$data[] = array(
    			'id' => $league->getId(),
    			'name' => $league->getName()
    		);
    	}

        return new JsonResponse(array(
        	'league' => $data
        ));
    }

    /**
     * @Route("/league/create")
     * @Method({"GET", "POST"})
	 */
    public function createAction(Request $request)
    {	
    	$data = array();
    	
    	return new JsonResponse(array(
        	'league' => $data
        ));	
    }

    /**
     *  @Route("/league/edit/{id}")
     * @Method({"GET", "POST"})
	 */
    public function editAction(Request $require $request)
    {
    	$data = array();
    	
    	return new JsonResponse(array(
        	'league' => $data
        ));
    }
    /**
     * @Method({"GET", "POST"})
	 */
    public function deleteAction(Request $request)
    {
    	$data = array();
    	
    	return new JsonResponse(array(
        	'league' => $data
        ));
    }


    private function getRepository($class = FootballLeague::class)
    {
    	return $this->getDoctrine()
    		->getRepository($class);
    }
}

<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use AppBundle\Entity\FootballLeague;

class FootballLeagueController extends Controller
{
    /**
     * @Route("/league")
     */
    public function indexAction(Request $request)
    {	
    	$serviceRepo = $this->get('service.entity.football_repository');

    	$leagues = $serviceRepo
    		->createQuery()
    		->findAllOrderedByName();

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
     * @Method({ "GET" })
	 */
    public function createAction(Request $request)
    {	
    	$data = array();
    	$name = $request->get('name', '');

    	if (!$name) {
    		throw new \Exception("Error Processing Request", 1);
    	}

    	try {

    		$serviceRepo = $this->get('service.entity.football_repository');

    		$league = $serviceRepo->createNew();
    		$league->setName($name);

    		$serviceRepo->save($league);

    		return new JsonResponse(array('message' => 'New Leaque Has Been Created!'), 200);

    	}
    	catch (EntityNotFoundException $e) {
    		throw $e;
    	}
    }
}

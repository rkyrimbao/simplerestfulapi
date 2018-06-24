<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

use AppBundle\Controller\BaseApiController;
use AppBundle\Entity\FootballLeague;

class FootballLeagueController extends BaseApiController
{
    /**
     * @Route("/leagues")
     */
    public function showAction(Request $request)
    {	
    	$this->validateRequest();

    	$serviceRepo = $this->get('service.entity_repository.football_league');

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
    	$this->validateRequest();

    	$data = array();
    	$name = $request->get('name', '');

    	if (!$name) {
    		throw new \Exception("Error Processing Request", 1);
    	}

    	try {

    		$serviceRepo = $this->get('service.entity_repository.football_league');

    		$league = $serviceRepo->createNew();
    		$league->setName($name);

    		$serviceRepo->save($league);

    		return new JsonResponse(array('message' => 'New Leaque Has Been Created!'), 200);

    	}
    	catch (\Exception $e) {
    		throw $e;
    	}
    }
}

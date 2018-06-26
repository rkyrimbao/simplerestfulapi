<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

use AppBundle\Controller\BaseApiController;
use AppBundle\Entity\FootballLeague;

class FootballLeagueController extends BaseApiController
{
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
            return new JsonResponse('Please provide league name.', Response::HTTP_NOT_ACCEPTABLE);
    	}

    	try {

    		$entityManager = $this->get('service.entity_manager.football_league');

    		$league = $entityManager->createNew();
    		$league->setName($name);

    		$entityManager->save($league);

            return new JsonResponse('Leage has been created.', Response::HTTP_OK);

    	}
    	catch (\Exception $e) {
    		throw $e;
    	}
    }

    /**
     * @Route("/league/{league}")
     */
    public function showLeagueAction(Request $request)
    {   
        $this->validateRequest();

        $leagueName = $request->get('league', '');

        $entityManager = $this->get('service.entity_manager.football_team');

        $teams = $entityManager
            ->getRepository()
            ->findTeamRelatedToLeague($leagueName);

        if (!$teams) {
            return new JsonResponse('No Results Found!');
        }

        $data = array();

        foreach ($teams as $team) {
            $league = $team->getFootballLeague();
            $data[$league->getName()][] = array(
                'id' => $team->getId(),
                'name' => $team->getName()
            );
        }

        return new JsonResponse(array(
            'league' => $data
        ));
    }

    /**
     * @Route("/leagues/{id}/delete")
     *
     */
    public function deleteAction(Request $request)
    {
        $this->validateRequest();

        $entityManager = $this->get('service.entity_manager.football_league');

        $league = $entityManager
            ->getRepository()
            ->findOneById($request->get('id', 0));

        if (!$league) {
            throw $this->createNotFoundException('League not found.');
        }

        $entityManager->delete($league);

        return new JsonResponse('Leage has been deleted.', Response::HTTP_OK);
    }
}

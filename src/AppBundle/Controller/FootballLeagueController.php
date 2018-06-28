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
     * @Method({ "POST" })
	 */
    public function createAction(Request $request)
    {	
    	$data = array();
    	$name = $request->get('name', '');

        $entityManager = $this->get('service.entity_manager.football_league');

    	if (!$name) {
            return new JsonResponse([ 
                'success' => false,
                'errors' => [
                    'name' => "Please provide data"
                ]
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        if ($entityManager->getRepository()->findByName($name)) {
            return new JsonResponse([
                'success' => false,
                'errors' => [
                    'name' => "{$name} already exists"
                ]
            ]);
        }

    	try {

            $league = $entityManager->createNew();
            $league->setName($name);

            $entityManager->save($league);

            return new JsonResponse([
                'success' => true,
                'message' => 'League has been created.', 
                'data' => [
                    'id' => $league->getId(),
                    'name' => $league->getName()
                ]
            ], Response::HTTP_OK);

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
        $leagueID = $request->get('league', '');

        $entityManager = $this->get('service.entity_manager.football_team');

        $league = $this->get('service.entity_manager.football_league')
            ->getRepository()
            ->findOneBy([ 'id' => $leagueID, 'status' => 1]);

        if (is_null($league)) {
            return new JsonResponse([
                'data' => [
                    'league' => []
                ]
            ]);
        }

        $teams = $entityManager
            ->getRepository()
            ->findByFootballLeague($league);

        $teamData = [];
        foreach ($teams as $team) {
            $teamData[] = [
                'id' => $team->getId(),
                'name' => $team->getName(),
                'strip' => $team->getStrip()
            ];
        }

        return new JsonResponse([
            'data' => [
                'league' => [
                    'id' => $league->getId(),
                    'name' => $league->getName(),
                ],
                'teams' => $teamData
            ]
        ]);
    }

    /**
     * @Route("/leagues/{id}/delete")
     *
     */
    public function deleteAction(Request $request)
    {
        $entityManager = $this->get('service.entity_manager.football_league');

        $league = $entityManager
            ->getRepository()
            ->findOneById($request->get('id', 0));

        if (!$league) {
            return new JsonResponse([
                'succes' => false,
                'errors' => [
                    'league' => 'Not found! with ID '.$request->get('id')
                ]
            ], Response::HTTP_OK);
        }

        $entityManager->delete($league);

        return new JsonResponse([
            'success' => true,
            'errors' => [],
        ], Response::HTTP_OK);
    }
}

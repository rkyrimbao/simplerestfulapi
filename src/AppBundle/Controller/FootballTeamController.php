<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityNotFoundException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use AppBundle\Controller\BaseApiController;

class FootballTeamController extends BaseApiController
{	
	/**
     * @Route("/league/create-team")
     * @Method({ "GET" })
	 */
	public function createAction(Request $request)
	{
		$this->validateRequest();

		$name = $request->get('name', '');
		$strip = $request->get('strip', '');
		$leagueName = $request->get('league', '');

		if (!$name || !$strip || !$leagueName) {
			throw $this->createNotFoundException('name, strip, and league is required.');
		}

		$foolballLeagueRepo = $this->get('service.entity_repository.football_league');
		$foolballTeamRepo = $this->get('service.entity_repository.football_team');

		try {
			$league = $foolballLeagueRepo
				->createQuery()
				->findOneByName($leagueName);

			/*
             * If No league to be found,
             * create the new league
             */
			if (!$league) {
				$league = $foolballLeagueRepo
					->createNew()
					->setName($leagueName);

				$foolballLeagueRepo->save($league);
			}

			$team = $foolballTeamRepo->createNew();

			$team->setName($name);
			$team->setStrip($strip);
			$team->setFootballLeague($league);

			$foolballTeamRepo->save($team);

			return new Response('Saved new team with id '. $team->getId());
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	/**
	 * @Route("/league/update-team")
	 * @Method({ "GET" })
	 */
	public function updateAction(Request $request)
	{
		$data  = $request->query->get('data');

		$id = $request->get('id', 0);

		$foolballTeamRepo = $this->get('service.entity_repository.football_team');
		$foolballLeagueRepo = $this->get('service.entity_repository.football_league');

		$team = $foolballTeamRepo
			->createQuery()
			->findOneById($id);

		if (!$team) {
			throw $this->createNotFoundException(sprintf('No team found for id %s', $id));
		}

		try {
			$name = $request->get('name', '');
			$strip = $request->get('strip', '');
			$leagueName = $request->get('league', '');

			$league = $foolballLeagueRepo
				->createQuery()
				->findOneByName($leagueName);

			if (!$league) {
				$league = $foolballLeagueRepo
					->createNew()
					->setName($leagueName);

				$foolballLeagueRepo->save($league);
			}

			if ($name) {
				$team->setName($name);
			}

			if ($strip) {
				$team->setStrip($strip);
			}

			$team->setFootballLeague($league);

			$foolballTeamRepo->save($team);

			return new Response('Team updated with id '. $team->getId());
		}
		catch (\Exception $e) {
			throw $e;
		}
	}
}
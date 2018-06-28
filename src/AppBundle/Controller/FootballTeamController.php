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
     * @Route("/team/create")
     * @Method({ "POST" })
	 */
	public function createAction(Request $request)
	{
		$name = $request->get('name', '');
		$strip = $request->get('strip', '');
		$leagueName = $request->get('league', '');

		if (!$name || !$strip || !$leagueName) {
			$errors = [];

			if (!$name) {
				$errors['name'] = '`name` is required';
			}

			if (!$strip) {
				$errors['strip'] = '`strip` is required';
			}
				
			if (!$leagueName) {
				$errors['league'] = '`league` is required';
			}

			return new JsonResponse([
				'errors' => $errors,
				'success' => false
			]);
		}

		$foolballLeagueManager = $this->get('service.entity_manager.football_league');
		$foolballTeamManager = $this->get('service.entity_manager.football_team');

		$footballTeam = $footballTeamManager->getRepository()->findOneByName(['name' => $name]);

		if (!is_null($footballTeam)) {
			return new JsonResponse([
				'errors' => [
					'name' => "{$name} already exists."
				],
				'success' => false
			]);
		}

		try {

			$league = $footballLeagueManager
				->getRepository()
				->findOneByName($leagueName);

			/*
             * If No league to be found,
             * create the new league
             */
			if (!$league) {
				$league = $foolballLeagueManager
					->createNew()
					->setName($leagueName);

				$foolballLeagueManager->save($league);
			}
			else {
				if (!$league->getStatus()) {
					return new JsonResponse([
						'success' => false,
						'errors' => [
							'league' => 'Please choose different league'
						]
					]);
				}
			}

			$team = $foolballTeamManager->createNew();

			$team->setName($name);
			$team->setStrip($strip);
			$team->setFootballLeague($league);

			$foolballTeamManager->save($team);

			return new JsonResponse(['data' => [
				'id' => $team->getId(),
				'name' => $team->getName(),
				'strip' => $team->getStrip(),
				'league' => [
					'id' => $team->getFootballLeague()->getId(),
					'name' => $team->getFootballLeague()->getName()
				]
			]]);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	/**
	 * @Route("/team/{id}/update")
	 * @Method({ "POST" })
	 */
	public function updateAction(Request $request)
	{
		$data  = $request->query->get('data');

		$id = $request->get('id', 0);

		$footballTeamManager = $this->get('service.entity_manager.football_team');
		$footballLeagueManager = $this->get('service.entity_manager.football_league');

		$team = $footballTeamManager
					->getRepository()
					->findOneById($id);

		$originalName = $team->getName();

		if (!$team) {
			throw $this->createNotFoundException(sprintf('No team found for id %s', $id));
		}

		try {
			$name = $request->get('name', '');
			$strip = $request->get('strip', '');
			$leagueName = $request->get('league', '');

			$league = $footballLeagueManager
						->getRepository()
						->findOneByName($leagueName);

			if (!$league) {
				$league = $footballLeagueManager
					->createNew()
					->setName($leagueName);

				$footballLeagueManager->save($league);
			}

			if (is_null($footballTeamManager->getRepository()->findOneByName($name))) {
				$team->setName($name);
			}

			if ($strip) {
				$team->setStrip($strip);
			}

			$team->setFootballLeague($league);

			$footballTeamManager->save($team, true);

			return new JsonResponse([
				'errors' => [],
				'data' => [
					'league' => [
						'id' => $league->getId(),
						'name' => $league->getName()
					],
					'team' => [
						'id' => $team->getId(),
						'name' => $team->getName(),
						'strip' => $team->getStrip()
					]
				]
			]);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}
}
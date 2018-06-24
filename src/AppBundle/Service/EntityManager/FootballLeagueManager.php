<?php

namespace AppBundle\Service\EntityManager;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\FootballLeague;

class FootballLeagueManager
{	
	const STATUS_DISABLED = 0;
	const STATUS_ENABLED = 1;

	protected $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function save(FootballLeague $league, $isUpdate = false)
	{
		$entityManager = $this->entityManager;

		if (!$isUpdate) {
			$entityManager->persist($league);
		}

		$entityManager->flush();
	}

	public function delete(FootballLeague $league)
	{
		$league->setStatus(self::STATUS_DISABLED);

		$this->save($league, true);
	}

	public function getRepository()
	{
		return $this
			->entityManager
			->getRepository(FootballLeague::class);
	}

	public function createQuery($sql)
	{
		return $this->entityManager->createQuery($sql);

	}

	public function createNew()
	{
		return new FootballLeague();
	}
}

<?php

namespace AppBundle\Service\EntityRepository;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\FootballLeague;

class FootballLeagueRepository
{	
	protected $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function save(FootballLeague $league)
	{
		$entityManager = $this->entityManager;

		$entityManager->persist($league);
		$entityManager->flush();
	}

	public function createQuery()
	{
		return $this
			->entityManager
			->getRepository(FootballLeague::class);
	}

	public function createNew()
	{
		return new FootballLeague();
	}
}
<?php

namespace AppBundle\Service\EntityRepository;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\FootballTeam;

class FootballTeamRepository
{	
	protected $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function save(FootballTeam $footballTeam, $isUpdate = false)
	{
		$entityManager = $this->entityManager;

		if (!$isUpdate) {
			$entityManager->persist($footballTeam);
		}

		$entityManager->flush();
	}

	public function delete(FootballTeam $footballTeam)
	{
		$entityManager = $this->entityManager;
		$entityManager->remove($footballTeam);
		$entityManager->flush();
	}

	public function createQuery()
	{
		return $this
			->entityManager
			->getRepository(FootballTeam::class);
	}

	public function createNew()
	{
		return new FootballTeam();
	}
}

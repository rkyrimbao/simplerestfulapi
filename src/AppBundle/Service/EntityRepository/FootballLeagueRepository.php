<?php

namespace AppBundle\Service\EntityRepository;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\FootballLeague;

class FootballLeagueRepository
{	
	protected $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	public function save(FootballLeague $league)
	{
		$em = $this
			->em
			->persist($league)
			->flush();
	}

	public function createQuery()
	{
		return $this
			->em
			->getRepository(FootballLeague::class);
	}

	public function createNew()
	{
		return new FootballLeague();
	}
}

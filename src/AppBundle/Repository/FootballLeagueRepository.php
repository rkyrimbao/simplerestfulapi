<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\FootballLeague;

class FootballLeagueRepository extends EntityRepository
{
	public function findAllOrderedByName()
	{
		return $this
			->getEntityManager()
			->createQuery('SELECT l FROM AppBundle:FootballLeague l ORDER BY l.name DESC')
			->getResult();
	}
}

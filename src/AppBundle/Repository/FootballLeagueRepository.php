<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\FootballLeague;

class FootballLeagueRepository extends EntityRepository
{
	const STATUS_DISABLED = 0;
	const STATUS_ENABLED = 1;

	public function findAllOrderedByName()
	{
		return $this
			->getEntityManager()
			->createQuery('SELECT l FROM AppBundle:FootballLeague l ORDER BY l.name DESC')
			->getResult();
	}

	public function getActiveLeagueOrderByName()
	{	
		$sql = '
			SELECT 
				l
			FROM
				AppBundle:FootballLeague l
			WHERE 
				l.status = :status
			ORDER BY
				l.name DESC
		';

		return $this
			->getEntityManager()
			->createQuery($sql)
			->setParameter('status', self::STATUS_ENABLED)
			->getResult();
	}
}

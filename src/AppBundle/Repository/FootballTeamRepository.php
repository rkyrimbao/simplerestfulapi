<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\FootballLeague;

class FootballTeamRepository extends EntityRepository
{
	const STATUS_DISABLED = 0;
	const STATUS_ENABLED = 1;

	public function findTeamRelatedToLeague($league)
	{
		$queryBuilder = $this
			->getEntityManager()
			->createQueryBuilder();

		$queryBuilder
			->select('t')
			->from('AppBundle\Entity\FootballTeam', 't')
			->leftJoin('AppBundle\Entity\FootballLeague', 'l', \Doctrine\ORM\Query\Expr\Join::WITH, 't.footballLeague = l.id')
			->andWhere('l.name = :league_name')
			->andWhere('l.status = :league_status')
			->setParameters(array(
				'league_name' => $league,
				'league_status' => self::STATUS_ENABLED
			))
			->orderBy('l.name', 'DESC');


		return $queryBuilder->getQuery()->getResult();
	}
}
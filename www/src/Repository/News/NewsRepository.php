<?php

declare(strict_types=1);

namespace App\Repository\News;

use App\Entity\News\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<News>
 *
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 */
class NewsRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, News::class);
	}

	public function save(News $entity): void
	{
		$this->getEntityManager()->persist($entity);
		$this->getEntityManager()->flush();
	}

	public function delete(News $entity): void
	{
		$this->getEntityManager()->remove($entity);
		$this->getEntityManager()->flush();
	}
}

<?php

namespace OC\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;

// N'oubliez pas ce use
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends EntityRepository
{
	public function myFindAll()
	{
		// Méthode 1 : en passant par l'EntityManager
		// $queryBuilder = $this->_em->createQueryBuilder()
		//   ->select('a')
		//   ->from($this->_entityName, 'a')
		// ;
		// Dans un repository, $this->_entityName est le namespace de l'entité gérée
		// Ici, il vaut donc OC\PlatformBundle\Entity\Advert

		// Méthode 2 : en passant par le raccourci (je recommande)
		//$queryBuilder = $this->createQueryBuilder('a');

		// On n'ajoute pas de critère ou tri particulier, la construction
		// de notre requête est finie

		// On récupère la Query à partir du QueryBuilder
		//$query = $queryBuilder->getQuery();

		// On récupère les résultats à partir de la Query
		//$results = $query->getResult();

		// On retourne ces résultats
		//return $results;

		//en plus simple:
		return $this
		    ->createQueryBuilder('a')
		    ->getQuery()
		    ->getResult()
		;
	}

	public function whereCurrentYear(QueryBuilder $qb)
	{
		$qb
		  ->andWhere('a.date BETWEEN :start AND :end')
		  ->setParameter('start', new \Datetime(date('Y').'-01-01'))  // Date entre le 1er janvier de cette année
		  ->setParameter('end',   new \Datetime(date('Y').'-12-31'))  // Et le 31 décembre de cette année
		;

		return $qb;
	}

	// Depuis le repository d'Advert
	public function getAdvertWithApplications()
	{
	  $qb = $this
	    ->createQueryBuilder('a')
	    ->leftJoin('a.applications', 'app')
	    ->addSelect('app')
	  ;

	  return $qb
	    ->getQuery()
	    ->getResult()
	  ;
	}

	public function getAdvertWithCategories(array $categoryNames)
	{
	  	$qb = $this->createQueryBuilder('a');

	    // On fait une jointure avec l'entité Category avec pour alias « c »
	    $qb
	      ->join('a.categories', 'c')
	      ->addSelect('c')
	    ;

	    // Puis on filtre sur le nom des catégories à l'aide d'un IN
	    $qb->where($qb->expr()->in('c.name', $categoryNames));
	    // La syntaxe du IN et d'autres expressions se trouve dans la documentation Doctrine

		return $qb
			->getQuery()
			->getResult()
		;
	}

	public function getAdverts($page, $nbPerPage)
	{
	  	$qb = $this->createQueryBuilder('a');

	    // On fait une jointure avec l'entité Category avec pour alias « c »
	    $qb
			->leftJoin('a.categories', 'c')
			->addSelect('c')
	    ;
	    // On fait une jointure avec l'entité Image avec pour alias « img »
	    $qb
			->leftJoin('a.image', 'img')
			->addSelect('img')
	    ;

	    // Puis on ne retourne que $limit résultats
    	$qb->orderBy('a.date', 'DESC');

		$qb
	      // On définit l'annonce à partir de laquelle commencer la liste
	      ->setFirstResult(($page-1) * $nbPerPage)
	      // Ainsi que le nombre d'annonce à afficher sur une page
	      ->setMaxResults($nbPerPage)
	    ;

	    // Enfin, on retourne l'objet Paginator correspondant à la requête construite
	    // (n'oubliez pas le use correspondant en début de fichier)
	    return new Paginator($qb, true);
	}
}

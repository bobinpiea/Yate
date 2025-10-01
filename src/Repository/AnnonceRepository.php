<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonce>
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    //    /**
    //     * @return Annonce[] Returns an array of Annonce objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Annonce
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }



    // Retrouver les Annonces par Type de transaction 
        public function findByTransactionNom(string $nom): array
        {
            return $this->createQueryBuilder('a')
                ->join('a.transaction', 't')
                ->where('LOWER(t.typeTransaction) = :nom')
                ->setParameter('nom', strtolower($nom))
                ->orderBy('a.datePublication', 'DESC')
                ->getQuery()
                ->getResult();
        }

    //  Retrouver les annonces par Transactione et NAture du bien
    public function findByTransactionAndNature(string $transactionNom, string $natureNom): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.transaction', 't')
            ->join('a.natureBien', 'n')
            ->where('t.typeTransaction = :transaction')
            ->andWhere('n.nom = :nature')
            ->setParameter('transaction', $transactionNom)
            ->setParameter('nature', $natureNom)
            ->orderBy('a.datePublication', 'DESC')         
            ->getQuery()
            ->getResult();
    }

    // Vente Terrain 

    // src/Repository/AnnonceRepository.php
        public function searchTerrainsVente(array $filters): array
        {
            $qb = $this->createQueryBuilder('a')
                ->join('a.transaction', 't')
                ->join('a.natureBien', 'n')
                ->where('t.typeTransaction = :transaction')
                ->andWhere('n.nom = :nature')
                ->setParameter('transaction', 'Vente')
                ->setParameter('nature', 'Terrain')
                ->orderBy('a.datePublication', 'DESC');

    
            if (!empty($filters['prixMin'])) {
                $qb->andWhere('a.prix >= :prixMin')
                ->setParameter('prixMin', $filters['prixMin']);
            }


            if (!empty($filters['prixMax'])) {
                $qb->andWhere('a.prix <= :prixMax')
                ->setParameter('prixMax', $filters['prixMax']);
            }

            if (!empty($filters['ville'])) {
                $qb->andWhere('a.ville LIKE :ville')
                ->setParameter('ville', '%' . $filters['ville'] . '%');
            }

            if (!empty($filters['commune'])) {
                $qb->andWhere('a.commune LIKE :commune')
                ->setParameter('commune', '%' . $filters['commune'] . '%');
            }

            if (!empty($filters['superficieMin'])) {
                $qb->andWhere('a.superficie >= :superficieMin')
                ->setParameter('superficieMin', $filters['superficieMin']);
            }

        
            if (!empty($filters['superficieMax'])) {
                $qb->andWhere('a.superficie <= :superficieMax')
                ->setParameter('superficieMax', $filters['superficieMax']);
            }

            if (!empty($filters['constructible'])) {
                $qb->andWhere('a.constructible = 1');
            }

            if (!empty($filters['viabilise'])) {
                $qb->andWhere('a.viabilise = 1');
            }

            return $qb->getQuery()->getResult();
        }



}

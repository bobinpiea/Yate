<?php

namespace App\Repository;

use App\Entity\Echanger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Echanger>
 */
class EchangerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Echanger::class);
    }

    //    /**
    //     * @return Echanger[] Returns an array of Echanger objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Echanger
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }



    public function findConversationsByUser($user): array
    {
        $messages = $this->createQueryBuilder('m')
            ->where('m.destinataire = :user OR m.expediteur = :user')
            ->setParameter('user', $user)
            ->orderBy('m.dateEnvoi', 'DESC')
            ->getQuery()
            ->getResult();

        $conversations = [];

        foreach ($messages as $message) {
            $annonce = $message->getAnnonce();
            $expediteur = $message->getExpediteur();
            $destinataire = $message->getDestinataire();

            if ($user === $expediteur) {
                $autre = $destinataire;
            } else {
                $autre = $expediteur;
            }

            $cle = $annonce->getId() . '-' . $autre->getId(); 

            if (!isset($conversations[$cle])) {
                $conversations[$cle] = [
                    'dernierMessage' => $message,
                    'annonce' => $annonce,
                    'autreUtilisateur' => $autre,
                ];
            }
        }

        return $conversations;
    }



    public function findConversationMessages($annonceId, $userId, $autreId): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.annonce = :annonceId')
            ->andWhere('
                (m.expediteur = :userId AND m.destinataire = :autreId)
                OR 
                (m.expediteur = :autreId AND m.destinataire = :userId)
            ')
            ->setParameter('annonceId', $annonceId)
            ->setParameter('userId', $userId)
            ->setParameter('autreId', $autreId)
            ->orderBy('m.dateEnvoi', 'ASC')
            ->getQuery()
            ->getResult();
    }


}

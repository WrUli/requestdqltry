<?php

namespace App\Repository;

use App\Entity\Musique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Musique>
 *
 * @method Musique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Musique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Musique[]    findAll()
 * @method Musique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Musique::class);
    }

    public function save(Musique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Musique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //ici le "?" sert à renvoyer quelque chose pouvant aussi être vide
    public function findAllNamesImagesIds(): ?array
    {
        //méthode createQueryBuilder, 'm' Musique, qui est appelé dans musiqueRepository
        return $this->createQueryBuilder('m')
            ->select('m.id', 'm.name', 'm.img')
            //récupère l'objet de la query créée au dessus
            ->getQuery()
            //exécute la requête rédigée au dessus et retourne un array avec chaques éléments
            ->getResult();
    }

    public function findAllMusicStyleArtist(): ?array
    {
        //méthode createQueryBuilder, 'm' Musique, qui est appelé dans musiqueRepository
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('m.id', 'm.name', 'm.img', 's.name AS style_name', 'ar.name AS artist_name')
            ->leftJoin('m.artist', 'ar')
            ->leftJoin('m.style', 's');
        return $queryBuilder->getQuery()->getResult();
    }

    public function findAllMusicStyleArtistById($id): ?array
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->select('m.id', 'm.name', 'm.img', 's.name AS style_name', 'ar.name AS artist_name')
            ->leftJoin('m.artist', 'ar')
            ->leftJoin('m.style', 's')
            ->andWhere('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery();
        //Là ça fonctionne pas parce que ça retourne des arrays
        // return $queryBuilder->getQuery()->getResult();
        //Avec ça, ça va, mais il faut rappeler le getQuery au dessus. Et ça ne renvoie pas dans un array vide et renvoie une seule entité, donc plus besoin du [0]
        return $queryBuilder->getOneOrNullResult();
    }

//    /**
//     * @return Musique[] Returns an array of Musique objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Musique
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

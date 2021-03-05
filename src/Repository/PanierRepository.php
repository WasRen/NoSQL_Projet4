<?php

namespace App\Repository;

use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Movie;
use Predis;

/**
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    public function callRedis($id, $MovieId) {
        $redis = new Predis\Client(array(
            "scheme" => "tcp",
            "host" => "localhost",
            "port" => 6379,
            "password"=>""
        ));
        

        if ($redis){
            
            $data = serialize($MovieId);
            
            $redis->rpush("panier-user".$id , $data);
            $response = $redis->lrange("panier-user".$id, 0, -1);
            
            //$redis->del("panier-userTest".$id); SUPPRIMER DES CHOSES
            
            
            
        }else{
            $response = false;
        }


        return $response;
    }

    // /**
    //  * @return Panier[] Returns an array of Panier objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Panier
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

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
    private $redis;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    public function callRedis() {
        $this->redis = new Predis\Client(array(
            "scheme" => "tcp",
            "host" => "localhost",
            "port" => 6379,
            "password"=>""
        ));
        

        // if ($redis){

        //     $data = serialize($MovieId);
        //                                                                 // Ajouter IF Vérif pour delete et l'ajout
        //     $redis->rpush("panier-user".$id , $data);
        //     $response = $redis->lrange("panier-user".$id, 0, -1);

        //     $redis->set("produit-panier".$MovieId , $quantité);
            
        //     $redis->del("panier-userTest".$id); SUPPRIMER DES CHOSES
            
            
            
        // }else{
        //     $response = false;
        // }


        // return $response;
    }


    public function addToCart($id, $MovieId) {
        if ($this->redis) {
            if(!$this->redis->exists("panier-user".$id))
            {
                $this->redis->flushDB();
                $this->redis->rpush("panier-user".$id, 1);
                $this->redis->expire("panier-user".$id, 300);
                $this->redis->rpop("panier-user".$id);
            }
            //key: user / value : id movie (list)
            $data = serialize($MovieId);
            $this->redis->rpush("panier-user".$id, $data);
           
            //key : id movie / value : quantity
            $this->redis->set($MovieId, 1);

            echo '<script>alert("Item added to cart)</script>';
        }
        else {
            echo '<script>alert("Failed to add this item into your cart. Please try again.")</script>';
        }
    }

    public function incrQuantity($MovieId) {
        $this->redis->incr($MovieId);
    }

    public function decrQuantity($MovieId) {
        $this->redis->decr($MovieId);
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

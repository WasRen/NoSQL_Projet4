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
        $this->redis = new Predis\Client(array(
            "scheme" => "tcp",
            "host" => "localhost",
            "port" => 6379,
            "password"=>""
        ));
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

    public function getCart($id)
    {
        if($this->redis->exists("panier-user".$id))
        {
            $response = $this->redis->hgetall("panier-user".$id);
        }
        else {
            $response = false;
        }
        return $response;
    }

    public function addToCart($id, $MovieId, $name) 
    {
        $arr = array('title' => $name, 'quantity' => 1);
        $arr = json_encode($arr);
        if (!$this->redis->exists("panier-user".$id))
        {
            $this->redis->hset("panier-user".$id, $MovieId, $arr);
            $this->redis->expire("panier-user".$id, 300);
        }
        else 
        {
            if ($this->redis->hexists("panier-user".$id, $MovieId))
            {
                echo '<script>alert("Item already added to cart.")</script>';
            }
            else 
            {
                $this->redis->hset("panier-user".$id, $MovieId, $arr);
                echo '<script>alert("Item added to cart)</script>';
            }
        }
    }

    public function incrQuantity($id, $MovieId) {
        $arr = $this->redis->hget("panier-user".$id, $MovieId);
        $arr = json_decode($arr, true);
        $arr['quantity']++;
        $arr = json_encode($arr);
        $arr = $this->redis->hset("panier-user".$id, $MovieId, $arr);
    }

    public function decrQuantity($id, $MovieId) {
        $arr = $this->redis->hget("panier-user".$id, $MovieId);
        $arr = json_decode($arr, true);

        if ($arr['quantity'] == 1) 
        {
            $this->redis->hdel("panier-user".$id, $MovieId);
        }
        else
        {
            $arr['quantity']--;
            $arr = json_encode($arr);
            $arr = $this->redis->hset("panier-user".$id, $MovieId, $arr);
        }
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

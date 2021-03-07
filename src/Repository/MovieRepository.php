<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Elasticsearch\ClientBuilder;
//require 'C:\wamp64\www\NoSQL_Projet4\vendor\autoload.php';

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public $client;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
        $client = ClientBuilder::create()
                                ->setHosts(["http://localhost:9200"])
                                ->build();
    }

    function getIndex() {
    }


    function callAPI($method, $url, $headers, $data)
        {
    $curl = curl_init();
    //$url = getAPIUrl()."".$url;

   
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        case "GET":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // Optional Authentication:
    //curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    // curl_setopt($curl, CURLOPT_HTTPAUTH, $auth);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    ////dump($data);
    ////dump($headers);
    ////dump($curl);
    $result = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($result, true);
    ////dump($result);
    return $result;
    }


/**
 * @param $url
 * @param $jsonString string object
 * @param $token
 */
function postJSON($url,$jsonString, $token = false){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonString);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $headers = array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonString));
    if($token){
        $headers[] = "Authorization: bearer " . $token;
    }
    $auth = "Authorization Bearer private-rm4kxe3md3juzetgko8qw3sn";
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HTTPAUTH, $auth);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}
function deleteAPI($url,$token = false){
    $url = getAPIUrl()."".$url;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $headers = array();
    if($token){
        $headers[] = "Authorization: bearer $token";
    }
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}


    // /**
    //  * @return Movie[] Returns an array of Movie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

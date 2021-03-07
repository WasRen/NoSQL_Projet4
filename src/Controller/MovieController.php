<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use App\Repository\PanierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/movie")
 * 
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/index", name="movie_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index(MovieRepository $movieRepository): Response
    {
        $data = '{
            "size" : 100,
            "query": {
                "match_all" : {}
            }
        }';

        $headers = array("Content-Type: application/json", "Content-Length: " . strlen($data));

        return $this->render('movie/index.html.twig', [
            'movies' => $movieRepository->callAPI("GET", "http://localhost:9200/movies/_doc/_search", $headers, $data),
        ]);
    }

    /**
     * @Route("/search", name="movie_search", methods={"GET", "POST"})
     * @IsGranted("ROLE_USER")
     */
    public function search(Request $request, MovieRepository $movieRepository): Response
    {
        $genres = array("Drama", "Action", "Animation", "Fantasy", "Comedy", "Horror", "Children", "Documentary", "Adventure", "Romance", "Sci-Fi", "War", "IMAX", "Crime", "Mystery", "(no genres listed)");
       
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie, array('genres' => $genres));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // récupérer les champs renseigné
            // créer la query dynamiquement
            $query = array();
            if ($title = $form->get('title')->getData()) {
                array_push($query,' {  "match" : { "title" : "' . $title . '" } }');
            }
            if ($price = $form->get('price')->getData()) {
                array_push($query,' {  "match" : { "price" : ' . $price . ' } }');
            }
            if ($ranking = $form->get('ranking')->getData()) {
                array_push($query,' {  "match" : { "rotten_tomatoes" : ' . $ranking. ' } }');
            }
            if ($genre = $form->get('genre')->getData()) {
                array_push($query,' {  "match" : { "genre" : "' . $genre . '" } }');
            }
            
            $query_str = implode(",", $query);
            $data = '{ "query" : { "bool" : { "must" : ['. $query_str .'] } } }';
            $headers = array("Content-Type: application/json", "Content-Length: " . strlen($data));

            return $this->render('movie/index.html.twig', [
                'movies' => $movieRepository->callAPI("GET", "http://localhost:9200/movies/_doc/_search", $headers, $data),
            ]);
        }

        return $this->render('movie/search.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
        $headers = array("Content-Type: application/json", "Content-Length: " . strlen($data));

        return $this->render('movie/index.html.twig', [
            'movies' => $movieRepository->callAPI("GET", "http://localhost:9200/movies/_doc/_search", $headers, $query),
        ]);
    }

    /**
     *@Route("/add_to_cart/{movieid}", name="ajout_panier", methods={"GET", "POST"})
     *@IsGranted("ROLE_USER")
     */

     public function addToCart($movieid, PanierRepository $panierRepository, MovieRepository $movieRepository) {


        $data = '{
            "_source" : [ "title"], 
            "query" : {
                "match" : {
                    "movieid" : ' . $movieid . '
                }
            }
        }';
        $headers = array("Content-Type: application/json", "Content-Length: " . strlen($data));

        $res = $movieRepository->callAPI("GET", "http://localhost:9200/movies/_doc/_search", $headers, $data);


        ////dump($res['hits']['hits'][0]['_source']['title']);

        $panierRepository->addToCart($this->getUser()->getId(), $movieid, $res['hits']['hits'][0]['_source']['title']);


        return $this->index($movieRepository);
     }


    /**
     *@Route("/publish", name="publish", methods={"GET", "POST"})
     *@IsGranted("ROLE_USER")
     */
    public function publish(MovieRepository $movieRepository) : Response {

        // $fileReader = fopen(__DIR__."/MOVIE_DB.txt", 'r');
        // $file_correct = fopen(__DIR__. "/MOVIE_DB_correct.txt", 'w');
        // $i = 1;
        // var_//dump("HELLLOO");
        // // var_//dump(json_decode($fileReader));
        // while (($line = fgets($fileReader)) !== false) {
        //     $index = '{ "index": { "_id": '.$i.'}}';
        //     var_//dump($index);
        //     fwrite($file_correct, $index . "\n");
        //     fwrite($file_correct, $line);
        //     $i++;
        // }

        // fclose($fileReader);
        // fclose($file_correct);
        // file_put_contents(__DIR__."/MOVIE_DB_correct.json", json_encode($file));
        
        // return $this->index($movieRepository);

        // $file = fopen(__DIR__.'/MOVIE_DB.txt','r');
        // while (!feof($file)){
        //     $line = fgets($file);
        //     $movieRepository->postJSON("http://localhost:9200/movies/_doc", $line, "private-rm4kxe3md3juzetgko8qw3sn");
        //     sleep(2);

        //     //$movieRepository->postJSON("http://localhost:9200/movies/_doc/_search", $line, "private-rm4kxe3md3juzetgko8qw3sn");
        //     // sleep(1);
        // }
        
        return $this->index($movieRepository);
    }

    /**
     * @Route("/new", name="movie_new", methods={"GET","POST"})
     */
    // public function new(Request $request): Response
    // {
    //     $movie = new Movie();
    //     $form = $this->createForm(MovieType::class, $movie);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($movie);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('movie_index');
    //     }

    //     return $this->render('movie/new.html.twig', [
    //         'movie' => $movie,
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/{movieid}", name="movie_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show($movieid, MovieRepository $movieRepository): Response
    {
        $data = '{
            "query" : {
                "match": {
                    "movieid" : "'. $movieid . '"
                }
            }
        }';

        $headers = array("Content-Type: application/json", "Content-Length: " . strlen($data));
        $movie = $movieRepository->callAPI("GET", "http://localhost:9200/movies/_doc/_search", $headers, $data);

        return $this->render('movie/show.html.twig', [
            'movie' => $movie['hits']['hits'][0]['_source'],
        ]);
    }

    /**
     * @Route("/{movieid}/edit", name="movie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Movie $movie): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movie_delete", methods={"DELETE"})
     */
    // public function delete(Request $request, Movie $movie): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($movie);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('movie_index');
    // }
}

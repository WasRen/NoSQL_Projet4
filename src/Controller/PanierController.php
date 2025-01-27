<?php

namespace App\Controller;

use RedisException;
use App\Entity\Movie;
use App\Entity\Panier;
use DateTimeInterface;
use App\Entity\Commande;
use App\Form\PanierType;
use AppBundle\Util\RedisHelper;
use App\Repository\PanierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/panier")
 * @IsGranted("ROLE_USER")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier_index", methods={"GET"})
     */
    public function index(PanierRepository $panierRepository): Response
    {
        $panier_items = array();
        $response = $panierRepository->getCart($this->getUser()->getId());
        //dump($response);
        if ($response) 
        {
            foreach ($response as $key => $value)
            {
                $value = json_decode($value, true);
                $panier_items[$key] = $value;
            }
        }
        //dump($panier_items);
        return $this->render('panier/index.html.twig', [
            'panier_items' => $panier_items,
        ]);
    }

    /**
     * @Route("/incr/{movie_id}", name="panier_incr", methods={"GET", "POST"})
     */
    public function cartIncr(PanierRepository $panierRepository, $movie_id): Response
    {
        $panierRepository->incrQuantity($this->getUser()->getId(), $movie_id);
        return $this->redirectToRoute('panier_index');
    }

    /**
     * @Route("/decr/{movie_id}", name="panier_decr", methods={"GET", "POST"})
     */
    public function cartDecr(PanierRepository $panierRepository, $movie_id): Response
    {
        $panierRepository->decrQuantity($this->getUser()->getId(), $movie_id);
        return $this->redirectToRoute('panier_index');
    }


    // /**
    //  *  @Route("/test", name="afficher_panier")
    //  */
    // public function test(PanierRepository $panierRepository): Response
    // {
    //     $panierRepository->callRedis();
    //     $movie = new Movie(); // importer les movies 
    //     $movie->setMovieId(1);
    //     $movie->setMovietitle("testNom");
    //     $panierRepository->addToCart($this->getUser()->getId(), $movie->getMovieid(), $movie->getMovietitle());
    //     $movie2 = new Movie(); // importer les movies 
    //     $movie2->setMovieId(2);
    //     $movie2->setMovietitle("testNom2");
    //     $panierRepository->addToCart($this->getUser()->getId(), $movie2->getMovieid(), $movie2->getMovietitle());
    //     $call = $panierRepository->getCart($this->getUser()->getId());
    //     $res = array();
    //     // foreach ($call as $key => $value)
    //     // {
    //     //     $value = json_decode($value, true);
    //     //     $res[$key] = $value;
    //     // }
    //     $panier_items = array();
    //         foreach ($call as $key => $value)
    //         {
    //             $value = json_decode($value, true);
    //             $panier_items[$key] = $value;
    //         }
    //         return $this->render('panier/index.html.twig', [
    //             'panier_items' => $panier_items,
    //         ]);

        
    // }

    /**
     * @Route("/new", name="panier_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('panier_index');
        }

        return $this->render('panier/new.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="panier_show", methods={"GET"})
     */
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="panier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Panier $panier): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('panier_index');
        }

        return $this->render('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="panier_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Panier $panier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('panier_index');
    }

    /**
     * @Route("/panier/commande", name="passerCommande", methods={"GET", "POST"})
     */
    public function passerCommande(PanierRepository $panierRepository){
        $call = $panierRepository->getCart($this->getUser()->getId());
        $em = $this->getDoctrine()->getManager();
        if ($call){
            $commande = new Commande();

            $datedujour = date("m.d.y");
            $commande->setDate($datedujour);
            $commande->setUser($this->getUser());
            
            
            $panier_items = array();
            //dump($call);
            
            
            foreach ($call as $key => $value)
                {
                    $value = json_decode($value, true);
                    $panier_items[$key] = $value;
            }
            $commande->setProduits($panier_items);

            $em->persist($commande);
            $em->flush();
            $panierRepository->deleteCart($this->getUser()->getId());
        }
        

        return $this->redirectToRoute('commande_index');
    }


}

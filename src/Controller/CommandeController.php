<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Response
    {
        $userId = $this->getUser()->getId();
        
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder();
        $query->select('c.id, c.date, c.produits');
        $query->from(Commande::class, 'c');
        $query->andWhere('c.user =' . $userId);
        $qb = $query->getQuery();
        
        $result = $qb->getResult();
        
        $commandes = $result;
        dump($commandes);
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use App\Entity\Partenaire;

class XalissController extends AbstractController
{
    /**
     * @Route("/xaliss", name="xaliss")
     */
    public function index()
    {
        return $this->render('xaliss/index.html.twig', [
            'controller_name' => 'XalissController',
        ]);
    }
    /**
     * @Route("/ajout_partenaire", name="ajout_partenaire")
     */
    public function ajout_partenaire(Request $request)
    {
        $valeur          = json_decode($request->getContent());
        $entityManager   = $this->getDoctrine()->getManager();

        $utilisateurRepo = $this->getDoctrine()->getRepository(Utilisateur::class);
        $utilisateur     = $utilisateurRepo->find($valeur->utilisateur);

        $partenaire      = new Partenaire();

        $partenaire->setUtilisateur($utilisateur);
        $partenaire->setRaisonSociale($valeur->raisonSociale);
        $partenaire->setNinea($valeur->ninea);
        $partenaire->setStatut($valeur->statut);
        $entityManager->persist($partenaire);
        $entityManager->flush();
        return new Response("le partenaire a été ajouté avec success");
    }
}

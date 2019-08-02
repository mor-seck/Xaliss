<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UtilisateurRepository;
use App\Repository\CompteRepository;
use App\Repository\DepotRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use App\Entity\Compte;
use App\Entity\Depot;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use phpDocumentor\Reflection\DocBlock\Serializer;
use App\Repository\PhoneRepository;
use Symfony\Component\Serializer\SerializerInterface;


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

    // CETTE PARTI DU CODE ME PERMET D'AJOUTER UN DEPOT
    
    /**
     * @Route("/ajouter_depot", name="ajouter_depot")
     */
    public function ajouter_depot(Request $request)
    {
        $values          = json_decode($request->getContent());
        $entityManager   = $this->getDoctrine()->getManager();

        $utilisateurRepo = $this->getDoctrine()->getRepository(Utilisateur::class);
        $utilisateur     = $utilisateurRepo->find($values->utilisateur);

        $compteRepo = $this->getDoctrine()->getRepository(Compte::class);
        $compte     = $compteRepo->find($values->compte);

        $depot = new Depot();

        $depot->setUtilisateur($utilisateur);
        $depot->setMontant($values->montant);
        $depot->setDateDepot(new \DateTime($values->date_depot));
        $depot->setCompte($compte);

        $entityManager->persist($depot);
        $entityManager->flush();
        return new Response("le dépot est effectué avec success");
    }

    /**
     * @Route("/depot/{id}", name="update_depo", methods={"PUT"})
     */
    public function updatedepot(SerializerInterface $serializer,Request $request, Compte $compte, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $compteUpdate = $entityManager->getRepository(Compte::class)->find($compte->getId());
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value) {
            if ($key && !empty($value)){
                $name = ucfirst($key);
                $setter = 'set'.$name;
                $compteUpdate->$setter($compte->getSolde()-$value);
            }
        }
        $errors = $validator->validate($compteUpdate);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->flush();
        return new JsonResponse($data);
    }
   

}

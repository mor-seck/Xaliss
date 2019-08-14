<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Partenaire;
use App\Entity\Utilisateur;
use App\Repository\DepotRepository;
use App\Repository\PhoneRepository;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\PersistentObject;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\VarExporter\Internal\Values;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use DeepCopy\Filter\Doctrine\DoctrineEmptyCollectionFilter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\DepotType;

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
     * @Route("/ajouter_depot", name="ajouter_depot",methodS={"POST"})
     */
    public function ajouter_depot(Request $request)
    {
        $values          = json_decode($request->getContent());
        $entityManager   = $this->getDoctrine()->getManager();
        $utilisateurRepo = $this->getDoctrine()->getRepository(Utilisateur::class);
        $utilisateur     = $utilisateurRepo->find($values->utilisateur);
        $compteRepo      = $this->getDoctrine()->getRepository(Compte::class)->find($values->compte);
        $depot           = new Depot();
        $depot->setUtilisateur($utilisateur);
        $depot->setMontant($values->montant);
        $depot->setDateDepot(new \DateTime);
        $depot->setCompte($compteRepo->setSolde($compteRepo->getSolde() + $values->montant));
        if ($values->montant < 75000) {
            return new response("la somme doit etres au minimum 75000");
        } else {
            $entityManager->persist($depot);
            $entityManager->flush();
            return new Response("le depot est effectue avec success");
        }
    }

    // CETTE PARTI DU CODE ME PERMET D'AJOUTER UN CAISSIER
    /**
     * @Route("/ajouter_caissier", name="ajouter_caissier",methodS={"POST"})
     * @IsGranted("ROLE_ADMIN_SUP")
     */
    public function ajouter_caissier(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $values         = json_decode($request->getContent());
        $entityManager  = $this->getDoctrine()->getManager();
        $partenaireRepo = $this->getDoctrine()->getRepository(Partenaire::class);
        $partenaire     = $partenaireRepo->find($values->partenaire);
        $caissier       = new Utilisateur();

        $caissier->setUsername($values->username);
        $caissier->setPassword($passwordEncoder->encodePassword($caissier, $values->password));
        $caissier->setRoles($values->roles);
        $caissier->setPrenom($values->prenom);
        $caissier->setNom($values->nom);
        $caissier->setAdresse($values->adresse);
        $caissier->setTelephone($values->telephone);
        $caissier->setEmail($values->email);
        $caissier->setStatut("Actif");
        $caissier->setPartenaire($partenaire);

        $entityManager->persist($caissier);
        $entityManager->flush();
        return new Response("le depot est effectue avec success");
    }

    // CETTE PARTI DU CODE ME PERMET DE MODIFIER UN DEPOT
    /**
     * @Route("/depot/{id}", name="update_depo", methods={"PUT"})
     * @IsGranted("ROLE_CAISSIER")
     * 
     */
    public function updatedepot(SerializerInterface $serializer, Request $request, Compte $compte, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $compteUpdate = $entityManager->getRepository(Compte::class)->find($compte->getId());
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value) {
            if ($key && !empty($value)) {
                $name = ucfirst($key);
                $setter = 'set' . $name;
                $compteUpdate->$setter($compte->getSolde() - $value);
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

    // CETTE PARTI DU CODE ME PERMET DE MODIFIER UN BLQUER UN PARTENAIRE
    /**
     * @Route("/utilisateur/{id}", name="update_utilisateur", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN_SUP")
     */
    public function bloquer_partenaire(SerializerInterface $serializer, Request $request, Utilisateur $utilisateur, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $utilisateurUpdate = $entityManager->getRepository(Utilisateur::class)->find($utilisateur->getId());
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value) {
            if ($key && !empty($value)) {
                $name = ucfirst($key);
                $setter = 'set' . $name;
                $utilisateurUpdate->$setter($value);
            }
        }
        $errors = $validator->validate($utilisateurUpdate);
        if (count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $entityManager->flush();
        return new JsonResponse($data);
    }
    // CETTE PARTI DU CODE ME PERMET DE MODIFIER UN BLQUER UN PARTENAIRE
    /**
     * @Route("/compte/{id}", name="update_compte", methods={"PUT"})
     */
    public function affectation_caiss(SerializerInterface $serializer, Request $request, Compte $compte, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $compteUpdate = $entityManager->getRepository(Compte::class)->find($compte->getId());
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value) {
            if ($key && !empty($value)) {
                $name = ucfirst($key);
                $setter = 'set' . $name;
                $compteUpdate->$setter($value);
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

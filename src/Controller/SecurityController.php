<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Form\CompteType;
use App\Entity\Partenaire;
use App\Entity\Utilisateur;
use App\Form\PartenaireType;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * cette methode me permet d'ajouter un partenaire
     * @Route("/register", name="register", methods={"POST"})
     * 
     */

    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {


        $partenaire = new Partenaire();
        $form  = $this->createForm(PartenaireType::class, $partenaire);
        $data = $request->request->all();
        $form->submit($data);
        if ($form->isSubmitted()) {
            $entityManager->persist($partenaire);
        }

        $utilisateur = new Utilisateur();
        $form1  = $this->createForm(UtilisateurType::class, $utilisateur);
        $form1->submit($data);
        if ($form1->isSubmitted()) {
            $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $data['password']));
            $utilisateur->setStatut("Actif");
            $utilisateur->setRoles(['ROLE_ADMIN_PARTENAIRE']);
            $file     = $request->files->all()['image'];
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('image'), $fileName);
            $utilisateur->setImage($fileName);
            $utilisateur->setPartenaire($partenaire);
            $entityManager->persist($partenaire);
        }

        $compte = new Compte();
        $form2  = $this->createForm(CompteType::class, $compte);
        $form2->submit($data);
        if ($form2->isSubmitted()) {
            $compte->setPartenaire($partenaire);
            $compte->setNumCompte(rand(000000000000000, 9999999999999999));
            $compte->setSolde(0);
            $compte->setUtilisateur($utilisateur);

            $entityManager->persist($compte);
            $entityManager->flush();
            $data = [
                'status1'  => 201,
                'message1' => "L'utilisateur a été créé sans probléme"
            ];
            return new JsonResponse($data, 201);
        }
        $data = [
            'status2'  => 500,
            'message2' => "L'utilisateur n'a pas été crée il se trouve que y a une erreur"
        ];

        return new JsonResponse($data, 500);
    }


    /**
     * cette methode me permet d'ajouter un un caissier
     * @Route("/AjouterCaissier", name="caissier", methods={"POST"})
     */
    public function caissier(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {

        $utilisateur = new Utilisateur();
        $form1  = $this->createForm(UtilisateurType::class, $utilisateur);
        $data = $request->request->all();
        $form1->submit($data);
        if ($form1->isSubmitted()) {
            $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $data['password']));
            $utilisateur->setStatut("Actif");
            $utilisateur->setRoles(['ROLE_CAISSIER']);
            $file     = $request->files->all()['image'];
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('image'), $fileName);
            $utilisateur->setImage($fileName);
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            $data = [
                'status1'  => 201,
                'message1' => "Votre Caissier a été créé sans probléme"
            ];
            return new JsonResponse($data, 201);
        }
        $data = [
            'status2'  => 500,
            'message2' => "L'utilisateur n'a pas été crée il se trouve que y a une erreur"
        ];

        return new JsonResponse($data, 500);
    }

    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $utilisateur = $this->getUser();
        return $this->json([
            'username' => $utilisateur->getUsername(),
            'password' => $utilisateur->getPassword()
        ]);
    }
}
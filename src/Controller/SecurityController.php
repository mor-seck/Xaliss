<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Persistence\PersistentObject;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use App\Entity\Partenaire;
use App\Entity\Compte;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $values = json_decode($request->getContent());


        if (isset($values->username, $values->password)) {

            $values        = json_decode($request->getContent());
            $entityManager = $this->getDoctrine()->getManager();

            $partenaire    = new Partenaire();
            $partenaire->setRaisonSociale($values->raisonSociale);
            $partenaire->setNinea($values->ninea);

            $utilisateur = new Utilisateur();
            $utilisateur->setUsername($values->username);
            $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $values->password));
            $utilisateur->setRoles($utilisateur->getRoles());
            $utilisateur->setPrenom($values->prenom);
            $utilisateur->setNom($values->nom);
            $utilisateur->setAdresse($values->adresse);
            $utilisateur->setTelephone($values->telephone);
            $utilisateur->setEmail($values->email);
            $utilisateur->setStatut($values->statut);
            $utilisateur->setPartenaire($partenaire);
            
            $compte   = new Compte();
            $compte->setPartenaire($partenaire);
            $compte->setNumCompte($values->num_compte);
            $compte->setSolde($values->solde);
            $compte->setUtilisateur($utilisateur);

            $entityManager->persist($utilisateur);
            $entityManager->persist($partenaire);
            $entityManager->persist($compte);
            $entityManager->flush();

            $data = [
                'status1' => 201,
                'message1' => "L'utilisateur a été créé"
            ];

            return new JsonResponse($data, 201);
        }
        $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner les clés username et password'
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

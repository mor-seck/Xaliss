<?php
namespace App\Controller;
use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Utilisateur;
use App\Entity\Partenaire;
use App\Repository\DepotRepository;
use App\Repository\PhoneRepository;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\PersistentObject;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\VarExporter\Internal\Values;

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

        $compteRepo      = $this->getDoctrine()->getgitRepository(Compte::class)->find($values->compte);
        $depot           = new Depot();

        $depot->setUtilisateur($utilisateur);
        $depot->setMontant($values->montant);
        $depot->setDateDepot(new \DateTime($values->date_depot));
        $depot->setCompte($compteRepo->setSolde($compteRepo->getSolde() + $values->montant));
        if($values->montant<75000){
            echo"la somme doit êtres au minimum 750000";
        }else{
            $entityManager->persist($depot);
            $entityManager->flush();
            return new Response("le dÃ©pot est effectuÃ© avec success");
        }
       
    }

    /**
     * @Route("/ajouter_caissier", name="ajouter_caissier",methodS={"POST"})
     */
    public function ajouter_caissier(Request $request, UserPasswordEncoderInterface $passwordEncoder){

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
        return new Response("le dépot est effectué avec success");
    }

     // CETTE PARTI DU CODE ME PERMET DE MODIFIER UN DEPOT
    /**
     * @Route("/depot/{id}", name="update_depo", methods={"PUT"})
     * 
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

     // CETTE PARTI DU CODE ME PERMET DE MODIFIER UN BLOquer un Partenaire
    /**
     * @Route("/utilisateur/{id}", name="update_utilisateur", methods={"PUT"})
     */
    public function bloquer_partenaire(SerializerInterface $serializer,Request $request, Utilisateur $utilisateur, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $utilisateurUpdate = $entityManager->getRepository(Utilisateur::class)->find($utilisateur->getId());
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value) {
            if ($key && !empty($value)){
                $name = ucfirst($key);
                $setter = 'set'.$name;
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
}
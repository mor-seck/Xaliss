<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Entity\Envoi;
use App\Entity\Compte;
use App\Entity\Retrait;
use App\Form\DepotType;
use App\Form\EnvoiType;
use App\Form\CompteType;
use App\Form\RetraitType;
use App\Entity\Utilisateur;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/api")
 */
class XalissController extends AbstractController
{


	private $security;

	public function __construct(Security $security)
	{
		$this->security = $security;
	}


	//==========================>FAiRE UN DEPOT===================================
	/**
	 * @Route("/depot", name="depot",methods={"POST"})
	 */
	public function depot(Request $request, EntityManagerInterface $entityManager)
	{
		$depot = new Depot();
		$form  = $this->createForm(DepotType::class, $depot);
		$data  = $request->request->all();

		$form->submit($data);
		if ($form->isSubmitted() && $form->IsValide()) {
			$depot->setDateDepot(new \DateTime);
			$utilisateur = new Utilisateur();
			$compte      = new Compte();
			$depot->setUtilisateur($utilisateur);
			$depot->setCompte($compte);
			$entityManager->persist($depot);
		}
	}
	//==========================>FIN UN DEPOT==============================================



	//==========================>LISTER PARTENAIRE==========================================
	/**
	 * @Route("/lister_partenaires", name="lister_partenaire", methods={"POST", "GET"})
	 */
	public function liste_partenaire(PartenaireRepository $partenaireRepository): Response
	{
		$partenaire  = $partenaireRepository->findAll();
		$encoders    = [new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$serializer  = new Serializer($normalizers, $encoders);
		$jsonObject  = $serializer->serialize($partenaire, 'json', [
			'circular_reference_handler' => function ($object) {
				return $object->getId();
			}
		]);
		return new Response($jsonObject, 200, array('Content-Type1' => 'application/json1'));
	}
	//==========================>FIN UN LISTER PARTENAIRE====================================



	//==========================>LISTER PARTENAIRE LA BON CODE=================================
	/**
	 * @Route("/lister_utilisateur", name="Lister_Partenaire", methods={"POST"})
	 */
	public function listeuser(UtilisateurRepository $utilisateurRepository): Response
	{
		$partenaire  = $utilisateurRepository->findAll();
		$encoders    = [new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$serializer  = new Serializer($normalizers, $encoders);
		$jsonObject  = $serializer->serialize($partenaire, 'json', [
			'circular_reference_handler' => function ($object) {
				return $object->getId();
			}
		]);
		return new Response($jsonObject, 200, array('Content-Type1' => 'application/json1'));
	}
	//==========================>FIN LISTER PARTENAIRE===============================================


	//==========================>AJOUTER UN COMPTE A UN PARTENAIRE===================================
	/**
	 * @Route("/ajouter/compte", name="Ajouter_Compte", methods={"POST"})
	 */
	public function creerCompte(Request  $request, PartenaireRepository  $partenaireRepository, ValidatorInterface $validator)
	{
		$values  = $request->request->all();
		$connect = $this->getDoctrine()->getManager();
		$compte  = new Compte();
		$form    = $this->createForm(CompteType::class, $compte);
		$form->submit($values);
		$utilisateur = $this->getUser();
		if ($form->isSubmitted()) {
			$compte->setNumCompte(rand(000000000000000, 9999999999999999));
			$compte->setSolde(0);
			$compte->setUtilisateur($utilisateur);
			$partenaire = $partenaireRepository->findByninea($values['partenaire']);
			if ($partenaire == NULL || $partenaire[0] == NULL) {
				return $this->json([
					'code' => 300,
					'Description' => 'Vous devez indiquer un Partenaire'
				]);
			}
			$compte->setPartenaire($partenaire[0]);

			$connect->persist($compte);
			$connect->flush();
			return $this->json([
				'code' => 200,
				'message' => 'Un nouveau Compte a été ajouté pour le partenaire'
			]);
		}
		return $this->handleView($this->view($validator->validate($form)));
	}
	//==========================>FIN AJOUT COMPTE POUR UN PARTENAIRE=================================


	//==========================>ENVOIE D'ARGENT=====================================================

	/**
	 * @Route("/Envoi", name="envoi",methods={"POST"})
	 */
	public function envoi(Request $request, ObjectManager $em)
	{
		$envoi = new Envoi();
		$form  = $this->createForm(EnvoiType::class, $envoi);
		$data  = $request->request->all();
		$form->submit($data);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($envoi);
			$em->flush();
			return new Response("la personne a été bien enregistré1");
		}
		return new Response("le depot est effectue avec success2");
	}

	//==========================>FIN ENVOIE=================================

	/**
	 * @Route("/ajoutter_compte", name="ajout_compte",methods={"POST"})
	 */
	public function ajout_compte(Request $request, ObjectManager $em)
	{
		$compte = new Compte();
		$form   = $this->createForm(CompteType::class, $compte);
		$data   = $request->request->all();
		$form->submit($data);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($compte);
			$em->flush();
			return new Response("la personne a été bien enregistré");
		}

		return new Response("le depot est effectue avec success1");
	}


	/**
	 * @Route("/retrait", name="retrait",methods={"POST"})
	 */
	public function retrait(Request $request, ObjectManager $em)
	{

		$retrait = new Retrait();
		$form  = $this->createForm(RetraitType::class, $retrait);
		$data = $request->request->all();
		$form->submit($data);
		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($retrait);
			$em->flush();
			return new Response("la personne a été bien enregistré");
		}

		return new Response("le depot est effectue avec success");
	}


	// CETTE PARTI DU CODE ME PERMET DE MODIFIER UN BLQUER UN PARTENAIRE
	/**
	 * @Route("/utilisateur/{id}", name="update_utilisateur", methods={"PUT"})
	 * 
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

	//CETTE METHODE ME PERTMET DE FAIRE UN DEPO
	/**
	 * @Route("/depot", name="depot", methods={"POST"})
	 */
	public function ajouter_depot(Request $request)
	{
		$values = json_decode($request->getContent());
		$entityManager = $this->getDoctrine()->getManager();
		$utilisateurRepo = $this->getDoctrine()->getRepository(Utilisateur::class);
		$utilisateur = $utilisateurRepo->find($values->utilisateur);
		$compteRepo = $this->getDoctrine()->getRepository(Compte::class)->find($values->compte);

		$depot = new Depot();
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

	/**
	 * Get the value of utilisateur
	 */
	public function getUtilisateur()
	{
		return $this->utilisateur;
	}
}
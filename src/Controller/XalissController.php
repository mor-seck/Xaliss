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
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class XalissController extends AbstractController
{

	//==========================>FAURE UN DEPOT=================================

	/**
	 * @Route("/depot", name="depot",methods={"POST"})
	 */
	public function depot(Request $request, EntityManagerInterface $entityManager)
	{
		$depot = new Depot();
		$form  = $this->createForm(DepotType::class, $depot);
		$data = $request->request->all();
		$form->submit($data);
		if ($form->isSubmitted() && $form->IsValide()) {
			$depot->setDateDepot(new \DateTime);
			$utilisateur = new Utilisateur();
			$compte = new Compte();
			$depot->setUtilisateur($utilisateur);
			$depot->setCompte($compte);
			$entityManager->persist($depot);
		}
	}
	//==========================>FIN UN DEPOT=================================


	//==========================>LISTER PARTENAIRE=================================
	/**
	 * @Route("/lister/partenaire", name="lister_partenaire", methods={"POST", "GET"})
	 */
	public function liste_partenaire(PartenaireRepository $partenaireRepository): Response
	{
		$partenaire  = $partenaireRepository->findAll();
		$encoders    = [new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$serializer  = new Serializer($normalizers, $encoders);

		// Serialize your object in Json
		$jsonObject = $serializer->serialize($partenaire, 'json', [
			'circular_reference_handler' => function ($object) {
				return $object->getId();
			}
		]);

		// For instance, return a Response with encoded Json
		return new Response($jsonObject, 200, array('Content-Type1' => 'application/json1'));
	}

	//==========================>FIN UN LISTER PARTENAIRE=================================


	//==========================>LISTER PARTENAIRE LA BON CODE=================================
	/**
	 * cette fonction met part
	 * @Route("/lister", name="lister", methods={"POST", "GET"})
	 */
	public function liste(UtilisateurRepository $utilisateurRepository): Response
	{
		$partenaire  = $utilisateurRepository->findAll();
		$encoders    = [new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$serializer  = new Serializer($normalizers, $encoders);

		// Serialize your object in Json
		$jsonObject = $serializer->serialize($partenaire, 'json', [
			'circular_reference_handler' => function ($object) {
				return $object->getId();
			}
		]);

		// For instance, return a Response with encoded Json
		return new Response($jsonObject, 200, array('Content-Type1' => 'application/json1'));
	}
	//==========================>FIN LISTER PARTENAIRE=================================


	//==========================>AJOUTER UN COMPTE A UN PARTENAIRE=================================
	/**
	 * @Route("/compte/ajouter", name="compte_ajout", methods={"POST"})
	 */
	public function creerCompte(Request $request, PartenaireRepository $partenaireRepository, ValidatorInterface $validator)
	{
		$values  = $request->request->all();
		$connect = $this->getDoctrine()->getManager();
		$compte  = new Compte();
		$form    = $this->createForm(CompteType::class, $compte);
		$form->submit($values);
		if ($form->isSubmitted()) {
			$compte->setNumCompte(rand(10000000, 99999));
			$compte->setSolde(0);
			$partenaire = $partenaireRepository->findByNinea($values['partenaire']);
			if ($partenaire == NULL || $partenaire[0] == NULL) {
				return $this->json([
					'code' => 300,
					'Description' => 'Il faut un partenaire pour ce compte ou ce partenaire n\'existe pas'
				]);
			}
			$compte->setPartenaire($partenaire[0]);
			$connect->persist($compte);
			$connect->flush();
			return $this->json([
				'code' => 200,
				'message' => 'Nouveau Compte Ajouté'
			]);
		}
		return $this->handleView($this->view($validator->validate($form)));
	}
	//==========================>FIN AJOUT COMPTE POUR UN PARTENAIRE=================================


	//==========================>ENVOIE D'ARGENT================================

	/**
	 * @Route("/envoi", name="envoi",methods={"POST"})
	 */
	public function envoi(Request $request, ObjectManager $em)
	{

		$envoi = new Envoi();

		$form  = $this->createForm(EnvoiType::class, $envoi);


		$data = $request->request->all();
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

		return new Response("le depot est effectue avec success");
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
}

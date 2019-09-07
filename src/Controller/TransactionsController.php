<?php

namespace App\Controller;

use App\Entity\Transactions;
use App\Form\TransactionsType;
use App\Repository\TarifRepository;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionsRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/api")
 */
class TransactionsController extends AbstractController

{
    /**
     * @Route("/transactions", name="transactions")
     */
    public function index()
    {
        return $this->render('transactions/index.html.twig', [
            'controller_name' => 'TransactionsController',
        ]);
    }

    //=====================FONCTION QUI NOUS PERMET DE FAIRE UN ENVOIE======================
    /**
     * @Route("/envoie", name="envoie", methods={"POST"})
     */
    public function envoie(Request $request, TarifRepository $tarifRepository, CompteRepository $compteRepository)
    {
        $values = $request->request->all();
        $montant = 0;

        $frais = 0;
        $i = 0;
        $connect = $this->getDoctrine()->getManager();
        $transaction = new Transactions();
        $form = $this->createForm(TransactionsType::class, $transaction);
        $errors = [];
        $form->submit($values);
        if ($form->isSubmitted()) {
            $montant = $transaction->getMontant();
            $teste = $tarifRepository->findAll();
            while ($i < count($teste)) {
                if ($teste[$i]->getBorneinferieur() <= $montant &&  $montant <= $teste[$i]->getBornesuperieur()) {
                    $frais = $teste[$i]->getValeur();
                    break;
                }
                $i++;
            }
            if (!is_numeric($montant)) {
                $errors[] = "On ne peut pas faire une transaction pour ce somme";
            }
            $transaction->setFrais($frais);
            $transaction->setType('Envoie');
            $transaction->setAgentenv($this->getUser());
            $transaction->setCode(rand(000000000, 999999999));
            $transaction->setDateenv(new \DateTime());
            $transaction->setCommissionetat(($frais * 30) / 100);
            $transaction->setCommissionSystem(($frais * 40) / 100);
            $transaction->setCommissionagentenv(($frais * 10) / 100);
            $comptepartenaire = $this->getUser()->getCompte();

            if ($comptepartenaire == NULL || $comptepartenaire->getPartenaire() != $this->getUser()->getPartenaire() || $comptepartenaire->getSolde() <= $montant) {
                $errors[] = 'Vous ne pouvez pas faire de transaction car on ne vous a pas assigné de compte ou Vous êtes un Hacker ou solde insuffisant';
            }
            if ($errors) {
                return $this->json([
                    'errors' => $errors
                ], 400);
            }
            $comptepartenaire->setSolde($comptepartenaire->getSolde() - ($montant) + (($frais * 10) / 100));
            $transaction->setCompte($comptepartenaire);
            $connect->persist($transaction);
            $connect->flush();
            return $this->json([
                'code' => 200,
                'message' => "Envoie Argent fait avec succès"
            ]);
        }
        return $this->json([
            'status' => 500,
            'message0' => "Une erreurs s'est produite: il y a des champs manquantes ou ce transaction existe déja"
        ]);
    }

    //=====================FONCTION QUI NOUS PERMET DE FAIRE UN RETRAIT======================
    /**
     * @Route("/retrait", name="retrait", methods={"POST"})
     */
    public function retrait(Request $request, CompteRepository $compteRepository, EntityManagerInterface $entityManager, Transactions $transaction = null)
    {
        $values = $request->request->all();
        var_dump($values);
        if ($transaction == NULL) {
            $errors[] = "Cette  Transaction n'existe pas";
        }
        if ($transaction->getType()) {
            $data = [
                'status' => 400,
                'message3' => "Le retrait de ce transaction est deja fait"
            ];
            return new JsonResponse($data, 200);
        }
        $envoyeur = $transaction->getUtilisateur();
        $form = $this->createForm(TransactionsType::class, $transaction);
        $form->submit($values);
        $errors = [];
        if ($form->isSubmitted()) {
            $transaction->setUtilisateur($envoyeur);
            $transaction->setUserRetrait($this->getUser());
            $transaction->setTotalEnvoyer($transaction->getTotalEnvoyer());
            $transaction->setDateRetrait(new \DateTime());
            $transaction->setType('retrait');
            $transaction->setMontantRetirer($transaction->getTotalEnvoyer() - $transaction->getCommissionTTC()->getValeur());
            $transaction->setCommissionRetrait(($transaction->getCommissionTTC()->getValeur() * 20) / 100);
            $comptepartenaire = $this->getUser()->getCompte();
            if ($comptepartenaire == NULL || $comptepartenaire->getPartenaire() != $this->getUser()->getPartenaire()) {
                $errors[] = 'Vous ne pouvez pas faire de transaction car on ne vous a pas assigné de compte ou Vous êtes un Hacker';
            }
            if (!$errors) {
                $comptepartenaire->setSolde($comptepartenaire->getSolde() + ($transaction->getTotalEnvoyer() - $transaction->getCommissionTTC()->getValeur()) + ($transaction->getCommissionTTC()->getValeur() * 20) / 100);
                $entityManager->persist($transaction);
                $compteEtat = $compteRepository->findByNumeroCompte(1960196019604);
                $compteWari = $compteRepository->findByNumeroCompte(2019201920190);
                $compteEtat[0]->setSolde($transaction->getCommissionEtat());
                $compteWari[0]->setSolde($transaction->getCommissionWari());
                $entityManager->flush();
                $data = [
                    'status3' => 200,
                    'message3' => "Le retrait est fait avec succès.",
                    'montant retirer' => $transaction->getMontantRetirer()
                ];
                return new JsonResponse($data, 200);
            } else {
                return $this->json([
                    'errors' => $errors
                ], 400);
            }
        }
    }

    /**
     * @Route("/liste_transaction", name="ListTransaction", methods={"POST", "GET"})
     */
    public function listertransaction(TransactionsRepository $transactionRepository): Response
    {
        $transactions = $transactionRepository->findAll();
        $encoders     = [new JsonEncoder()];
        $normalizers  = [new ObjectNormalizer()];
        $serializer   = new Serializer($normalizers, $encoders);
        $jsonObject   = $serializer->serialize($transactions, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response($jsonObject, 200, array('Content-Type1' => 'application/json1'));
    }
}
<?php
namespace App\Controller;
use App\Entity\Partenaire;
use App\Form\PartenaireType;
use App\Repository\PartenaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Compte;
use App\Entity\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
/**
* @Route("/api/partenaire")
* @Security("has_role('ROLE_AdminWari')")
*/
class PartenaireController extends AbstractFOSRestController
{
    /**
     * @Route("/", name="partenaire_index", methods={"GET"})
     */
    public function index(PartenaireRepository $partenaireRepository): Response
    {
        $partenaires=$partenaireRepository->findAll();
        return $this->handleView($this->view($partenaires));
    }
    /**
     * @Route("/ajout", name="partenaire_ajout", methods={"GET","POST"})
     */
    public function new(ValidatorInterface $validator, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $partenaire = new Partenaire();
        $user = new User();
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);
        $data=json_decode($request->getContent(),true);
        $user->setImageName("null");
        if(!$data){
            $data=$request->request->all(); 
        }
        $form->submit($data);
        $file=$request->files->all()['imageFile'];
        if (! in_array($file->getMimeType(), array('image/jpeg','image/jpg','image/png'))){
            return $this->handleView($this->view([$this->message=>'choisissez une image'],Response::HTTP_UNAUTHORIZED));
        }
        $errors = $validator->validate($partenaire);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $partenaire->setStatus('Actif');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($partenaire);
            $entityManager->flush();
            //creation compte
            $compte= new Compte();
            $compte->setPartenaire($partenaire);
            $compte->setCreatedAt(new \Datetime());
            $compte->setSolde(0);
            $compte->setNumeroCompte("000".date("d").date("m").date("Y").date("H").date("i").date("s"));
            $entityManager->persist($compte);
            $entityManager->flush();
            //creation user
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,'passer'
                )
            );
            
            $user->setImageFile($file);
            $user->setEmail($partenaire->getEmailPersonneRef());
            $user->setRoles(['ROLE_SuperAdminPartenaire']);
            $user->setCompte($compte);
            $user->setNombreConnexion(0);
            $user->setStatus('Actif');
            $user->setNomComplet($partenaire->getNomCompletPersonneRef());
            $user->setAdresse($partenaire->getAdressePersonneRef());
            $user->setCni($partenaire->getCniPersonneRef());
            $user->setTelephone($partenaire->getTelephoneRef());
            $user->setPartenaire($partenaire);
            $file=$request->files->all()['imageFile'];
            $user->setImageFile($file);
            
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->handleView($this->view(['status'=>'ok'],Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }
    /**
     * @Route("/{id}", name="partenaire_show", methods={"GET"})
     */
    public function show(Partenaire $partenaire): Response
    {
        return $this->handleView($this->view($partenaire));
    }
    /**
     * @Route("/{id}/edit", name="partenaire_edit", methods={"GET","POST"})
     */
    public function edit(ValidatorInterface $validator,Request $request, Partenaire $partenaire): Response
    {
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);
        $data=json_decode($request->getContent(),true);
        $form->submit($data);
        $errors = $validator->validate($partenaire);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->handleView($this->view(['status'=>'ok'],Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }
}

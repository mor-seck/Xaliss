<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Form\BeneficiaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BeneficiaireController extends AbstractController
{
    /**
     * @Route("/beneficiaire", name="beneficiaire")
     */
    public function index(Request $request)
    {
        $beneficiaire = new Beneficiaire();
        $form = $this->createForm(BeneficiaireType::class, $beneficiaire);
        $data = $request->request->all();
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($beneficiaire);
            $em->flush();
            return new Response('oh');
        }
        return new Response('ko');
        
    
    }
}

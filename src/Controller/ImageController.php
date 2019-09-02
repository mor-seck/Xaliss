<?php

namespace App\Controller;

use App\Entity\Photo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;  
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageController extends AbstractController
{
    /**
     * @Route("/image", name="image")
     */
    public function index(Request $request)
    {
        $photo = new Photo();
        $form = $this->createFormBuilder($photo)
            
            ->add('photo', FileType::class, array('label' => 'Photo (png, jpeg)'))
            ->add('save', SubmitType::class, array('label' => 'Submit'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $photo->getPhoto();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('/public/images/products'), $fileName);
            $photo->setPhoto($fileName);
            return new Response("User photo is successfully uploaded.");
        } else {
            return $this->render('student/new.html.twig', array(
                'form' => $form->createView(),
            ));
        } 
    }
}

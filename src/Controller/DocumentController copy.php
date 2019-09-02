<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
class DocumentController extends AbstractController
{
    /**
     * @Route("/document", name="document")
     */
    public function index()
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml("C'est ici que vous allez mettre le contrat ");
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream();
    }
}

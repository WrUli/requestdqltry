<?php

namespace App\Controller;

use App\Entity\Style;
use App\Form\StyleType;
use App\Repository\StyleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StyleController extends AbstractController
{
    #[Route('/style', name:'app_style')]
    public function style(Request $request, StyleRepository $styleRepository)
    {
        $style = new Style();
        $styleForm = $this->createForm( StyleType::class, $style );
        $styleForm->handleRequest($request);
        
        if ($styleForm->isSubmitted() && $styleForm->isValid())
        {
            $styleRepository->save($style, true);
            return $this->redirectToRoute('app_style');
        }
        return $this->render('style/index.html.twig',[
            'styles' => $styleRepository->findAll(),
            'form' => $styleForm->createView()
        ]);
    }
}

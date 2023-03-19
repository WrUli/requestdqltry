<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtistController extends AbstractController
{
    #[Route('/artist', name: 'app_artist')]
    public function artist(Request $request, ArtistRepository $artistRepository)
    {
        $artist = new Artist();
        $artistForm = $this->createForm( ArtistType::class, $artist );
        $artistForm->handleRequest($request);
        
        if ($artistForm->isSubmitted() && $artistForm->isValid())
        {
            $artistRepository->save($artist, true);
            return $this->redirectToRoute('app_artist');
        }
        return $this->render('artist/index.html.twig',[
            'artists' => $artistRepository->findAll(),
            'form' => $artistForm->createView()
        ]);
    }
}

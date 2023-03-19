<?php

namespace App\Controller;

use App\Entity\Musique;
use App\Form\MusiqueType;
use App\Repository\MusiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MusiqueController extends AbstractController
{
    #[Route('/', name:'app_musique')]
    public function musique(Request $request, MusiqueRepository $musiqueRepository)
    {
        $musique = new Musique();
        $musiqueForm = $this->createForm( MusiqueType::class, $musique );
        $musiqueForm->handleRequest($request);
        
        if ($musiqueForm->isSubmitted() && $musiqueForm->isValid())
        {   
            $file = $musiqueForm->get('img')->getData();
            if ($file) 
            {
                $originalNameFile = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFileName = $originalNameFile.'_'.uniqid().'.'.$file->guessExtension();
                $musique->setImg($newFileName);
                //Important pour path des images & co
                //Dossier config -> Fichier services.yaml -> pour le chemin du repository à partir de la racine
                //Important pour path des images & co
                $file->move($this->getParameter('musique_directory'),$newFileName);
            }
            $musiqueRepository->save($musique, true);
            return $this->redirectToRoute('app_musique');
            //Faire sa requête dql
            //selectionner que le "name" et "id" de la table music
            //par ordre alphabétique
        }
        return $this->render('musique/index.html.twig',[
            'musiques' => $musiqueRepository->findAllMusicStyleArtist(),
            // 'musiques' => $musiqueRepository->findAllNamesImagesIds(),
            // 'musiques' => $musiqueRepository->findAll(),
            'form' => $musiqueForm->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_musique_show')]
    public function showArticleById(MusiqueRepository $musiqueRepository,int $id)
    {
        $musique = $musiqueRepository->findOneBy(['id' => $id]);
    
        return $this->render('musique/show.html.twig', [
            'musique' => $musiqueRepository->findAllMusicStyleArtistById($id)
        ]);
    }
    
}

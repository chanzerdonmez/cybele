<?php

namespace App\Controller;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ArtistsController extends AbstractController
{
    /**
     *@Route("/artists/new", name="artists_new")
     */
    public function new(Request $request, ManagerRegistry $doctrine)
    {

    
    $artiste = new Artiste;

    $artiste->setPublishedAt(new DateTimeImmutable());

    $formArtiste = $this->createForm(ArtisteType::class, $artiste);

    $formArtiste->handleRequest($request);

    if ($formArtiste->isSubmitted() && $formArtiste->isValid())
    {

        $entityManager = $doctrine->getManager();

        $entityManager->persist($artiste);

        $entityManager->flush();

        return $this->redirectToRoute('artiste_list');

    }

    return $this->render('artists/form-new.html.twig', [
        "formArtiste" => $formArtiste->createView()
    ]);

    }


    /**
     * @Route ("/artists/edit/{id}", name="artiste_edit")
     */
    public function edit($id, ManagerRegistry $doctrine, Request $request)
    {
        $artiste = $doctrine->getRepository(Artiste::class)->find($id);
        
        $formArtiste = $this->createForm(ArtisteType::class, $artiste);
        $formArtiste->handleRequest($request);
        if($formArtiste->isSubmitted() && $formArtiste->isValid())

        {
            $entityManager = $doctrine->getManager();

            $entityManager->flush();
            
            return $this->redirectToRoute('artiste_list');
        }

        $formArtiste = $this->createForm(ArtisteType::class, $artiste);

        return $this->render ('artists/form-edit.html.twig', [
            "formArtiste" => $formArtiste->createView(),
        ]);

    }



    /**
     * @Route("/artists/delete/{id}", name="artiste_delete")
     */
    public function delete($id, ManagerRegistry $doctrine)
    {
        
        $entityManager = $doctrine->getManager();

        
        $artiste = $doctrine->getRepository(Artiste::class)->find($id);

        
        $entityManager->remove($artiste);

        
        $entityManager->flush();

      

      
        return $this->redirectToRoute('artiste_list');

    }




    ##READ : ALL
    /**
     * @Route("/artists/list", name="artiste_list")
     */
    public function readAll(ManagerRegistry $doctrine)
    {   
        $this->denyAccessUnlessGranted('ROLE_USER');
        #Etape 1 : Récupérer tous les livres
        $artistes = $doctrine->getRepository(Artiste::class)->findAll();

        #Envoyer les livres récupérés dans une page, dans laquelle vous listerez tous les livress
        return $this->render("artists/list.html.twig", [
            "artistes" => $artistes
        ]);
    }



    /**
     * @Route("/artists/{id}", name="artiste_detail", requirements={"id"="\d+"})
     */
    public function detail($id, ManagerRegistry $doctrine)
    {
        $artistes = $doctrine->getRepository(Artiste::class)->find($id);
        return $this->render('artists/item.html.twig', [
            "artistes" => $artistes
        ]);
    }

    /**
     * @Route("/artistes", name="artists") 
     */
    public function artistes(ManagerRegistry $doctrine)
    {
        $artistes = $doctrine->getRepository(Artiste::class)->findAll();
        return $this->render('artists/artistes.html.twig', [
            "artistes" => $artistes
        ]);
    }







}
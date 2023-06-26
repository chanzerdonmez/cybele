<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use App\Form\OeuvreType;
use DateTime;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products/new", name="products_new")
     */
    public function new(Request $request, ManagerRegistry $doctrine)
    {

        $oeuvre = new Oeuvre;

        $oeuvre->setPublishedAt(new DateTimeImmutable());

        $formOeuvre = $this->createForm(OeuvreType::class, $oeuvre);

        $formOeuvre->handleRequest($request);

        if ($formOeuvre->isSubmitted() && $formOeuvre->isValid())
        {

            $entityManager = $doctrine->getManager();

            $entityManager->persist($oeuvre);

            $entityManager->flush();

            return $this->redirectToRoute('oeuvre_list');

        }

        return $this->render('products/form-new.html.twig', [
            "formOeuvre" => $formOeuvre->createView()
        ]);
    }
    
    /**
     * @Route ("/products/edit/{id}", name="oeuvre_edit")
     */
    public function edit($id, ManagerRegistry $doctrine, Request $request)
    {
        $oeuvre = $doctrine->getRepository(Oeuvre::class)->find($id);
        
        $formOeuvre = $this->createForm(OeuvreType::class, $oeuvre);
        $formOeuvre->handleRequest($request);
        if($formOeuvre->isSubmitted() && $formOeuvre->isValid())

        {
            $entityManager = $doctrine->getManager();

            $entityManager->flush();
            
            return $this->redirectToRoute('oeuvre_list');
        }

        $formOeuvre = $this->createForm(OeuvreType::class, $oeuvre);

        return $this->render ('products/form-edit.html.twig', [
            "formOeuvre" => $formOeuvre->createView(),
        ]);

    }



    /**
     * @Route("/products/delete/{id}", name="oeuvre_delete")
     */
    public function delete($id, ManagerRegistry $doctrine)
    {
        
        $entityManager = $doctrine->getManager();

        
        $oeuvre = $doctrine->getRepository(Oeuvre::class)->find($id);

        
        $entityManager->remove($oeuvre);

        
        $entityManager->flush();

      

      
        return $this->redirectToRoute('oeuvre_list');

    }


    ##READ : ALL
    /**
     * @Route("/products/list", name="oeuvre_list")
     */
    public function readAll(ManagerRegistry $doctrine)
    {   
        $this->denyAccessUnlessGranted('ROLE_USER');
        #Etape 1 : Récupérer tous les livres
        $oeuvres = $doctrine->getRepository(Oeuvre::class)->findAll();

        #Envoyer les livres récupérés dans une page, dans laquelle vous listerez tous les livress
        return $this->render("products/list.html.twig", [
            "oeuvres" => $oeuvres
        ]);
    }



    /**
     * @Route("/products/{id}", name="oeuvre_detail", requirements={"id"="\d+"})
     */
    public function detail($id, ManagerRegistry $doctrine)
    {
        $oeuvres = $doctrine->getRepository(Oeuvre::class)->find($id);
        return $this->render('products/item.html.twig', [
            "oeuvres" => $oeuvres
        ]);
    }

    /**
     * @Route("/oeuvres", name="oeuvres") 
     */
    public function oeuvres(ManagerRegistry $doctrine)
    {
        $oeuvres = $doctrine->getRepository(Oeuvre::class)->findAll();
        return $this->render('products/oeuvres.html.twig', [
            "oeuvres" => $oeuvres
        ]);
    }

}
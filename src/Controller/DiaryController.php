<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Form\AgendaType;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

class DiaryController extends AbstractController
{
  

/**
     * @Route("/diary/new", name="diary_new")
     */
    public function new(Request $request, ManagerRegistry $doctrine)
    {

        $agenda = new Agenda;

        $agenda->setPublishedAt(new DateTimeImmutable());

        $formAgenda = $this->createForm(AgendaType::class, $agenda);

        $formAgenda->handleRequest($request);

        if ($formAgenda->isSubmitted() && $formAgenda->isValid())
        {

            $entityManager = $doctrine->getManager();

            $entityManager->persist($agenda);

            $entityManager->flush();

            return $this->redirectToRoute('agenda_list');

        }

        return $this->render('diary/form-new.html.twig', [
            "formAgenda" => $formAgenda->createView()
        ]);
    }



    /**
     * @Route ("/diary/edit/{id}", name="diary_edit")
     */
    public function edit($id, ManagerRegistry $doctrine, Request $request)
    {
        $agenda = $doctrine->getRepository(Agenda::class)->find($id);
        
        $formAgenda = $this->createForm(AgendaType::class, $agenda);
        $formAgenda->handleRequest($request);
        if($formAgenda->isSubmitted() && $formAgenda->isValid())

        {
            $entityManager = $doctrine->getManager();

            $entityManager->flush();
            
            return $this->redirectToRoute('agenda_list');
        }

        $formAgenda = $this->createForm(AgendaType::class, $agenda);

        return $this->render ('diary/form-edit.html.twig', [
            "formAgenda" => $formAgenda->createView(),
        ]);

    }





    /**
     * @Route("/diary/delete/{id}", name="agenda_delete")
     */
    public function delete($id, ManagerRegistry $doctrine)
    {
        
        $entityManager = $doctrine->getManager();

        
        $agenda = $doctrine->getRepository(Agenda::class)->find($id);

        
        $entityManager->remove($agenda);

        
        $entityManager->flush();

      

      
        return $this->redirectToRoute('agenda_list');

    }




    ##READ : ALL
    /**
     * @Route("/diary/list", name="agenda_list")
     */
    public function readAll(ManagerRegistry $doctrine)
    {   
        $this->denyAccessUnlessGranted('ROLE_USER');
        #Etape 1 : Récupérer tous les livres
        $agendas = $doctrine->getRepository(Agenda::class)->findAll();

        #Envoyer les livres récupérés dans une page, dans laquelle vous listerez tous les livress
        return $this->render("diary/list.html.twig", [
            "agendas" => $agendas
        ]);
    }




    /**
     * @Route("/diary/{id}", name="diary_detail", requirements={"id"="\d+"})
     */
    public function detail($id, ManagerRegistry $doctrine)
    {
        $agendas = $doctrine->getRepository(Agenda::class)->find($id);
        return $this->render('diary/item.html.twig', [
            "agendas" => $agendas
        ]);
    }

    /**
     * @Route("/agenda", name="agenda") 
     */
    public function agendas(ManagerRegistry $doctrine)
    {
        $agendas = $doctrine->getRepository(Agenda::class)->findAll();
        return $this->render('diary/agenda.html.twig', [
            "agendas" => $agendas
        ]);
    }

}
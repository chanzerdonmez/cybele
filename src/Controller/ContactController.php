<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{


    /**
     * @Route("/contact", name="app_contact")
     */
    public function new(Request $request, ManagerRegistry $doctrine, MailerInterface $mailer): Response
    {
    
        $contact = new Contact;
    
        $formContact = $this->createForm(ContactType::class, $contact);

        $formContact->handleRequest($request);
        if($formContact->isSubmitted() && $formContact->isValid())

        {
            $entityManager = $doctrine->getManager();

            $entityManager->persist($contact);
            $entityManager->flush();
            
            #Etape : Envoie de lemail

            $email = (new TemplatedEmail())
            
    ->from($contact->getEmail())
    ->to('contact@monsite.com')
    ->subject('Demande de contact')
    ->htmlTemplate('emails/contact.html.twig')
    ->context([
        "contact" => $contact
    ])
;


            $mailer->send($email);

        $this->addFlash('contact_success', "Le mail est ");

            return $this->redirectToRoute('app_contact');
        }

        
        return $this->render('contact/index.html.twig', [
            "formContact" => $formContact->createView()
        ]);


    }






}

<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            // Envoyer l'email
           $email = (new Email())
                ->from('ghislainvachet@yahoo.fr') // Une adresse fixe autorisée par votre serveur SMTP
                ->to('ghislainvachet@yahoo.fr')
                // ->replyTo($contact['email']) // Adresse de l'expéditeur pour les réponses
                ->subject('Nouveau message de contact')
                ->text("Nom : ".$contact['name']."\nEmail : ".$contact['email']."\nMessage : ".$contact['message']);
            $mailer->send($email);

           return $this->redirectToRoute('app_home');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
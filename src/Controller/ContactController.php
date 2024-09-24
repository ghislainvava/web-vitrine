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
            ->subject('Nouveau message de contact')
            ->text("Nom : ".$contact['name']."\nEmail : ".$contact['email']."\nMessage : ".$contact['message']);
        $mailer->send($email);

        // Après l'envoi de l'email, redirigez vers une autre page (ou affichez un message de confirmation dans le turbo-frame)
        return $this->redirectToRoute('app_home');
    }

    // Si la requête cible un turbo-frame
    if ($request->headers->get('Turbo-Frame') === 'contact-form-frame') {
        // Renvoie uniquement le formulaire pour qu'il soit inséré dans le turbo-frame
        error_log("Requête Turbo détectée !");
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Si ce n'est pas une requête Turbo, renvoie la page complète
    return $this->render('contact/index.html.twig', [
        'form' => $form->createView(),
    ]);
}
}
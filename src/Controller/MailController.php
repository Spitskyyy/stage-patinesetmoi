<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\ContactType;

class MailController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $userEmail = $data['email']; 
            $userName = $data['nom'];
            $message = $data['message'];

            // Création de l'email
            $email = (new Email())
                ->from($userEmail)  
                ->to('stagetestmail1@gmail.com')
                ->subject('Nouveau message de contact')
                ->text(
                    "Vous avez reçu un nouveau message de contact :\n\n" .
                    "Nom : $userName\n" .
                    "Email : $userEmail\n\n" .
                    "Message :\n$message"
                );

            $mailer->send($email);

            // Message flash pour confirmer l'envoi
            $this->addFlash('success', 'Votre message a été envoyé avec succès !');

            return $this->redirectToRoute('contact');
        }

        return $this->render('mail/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

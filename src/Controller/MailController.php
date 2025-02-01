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

            $email = (new Email())
                ->from($data['email'])
                ->to('destinataire@example.com') // Remplace avec ton adresse e-mail
                ->subject('Nouveau message de contact')
                ->text($data['message']);

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a été envoyé avec succès !');

            return $this->redirectToRoute('contact');
        }

        return $this->render('mail/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}


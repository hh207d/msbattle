<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ApiController extends AbstractController
{
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = new User($email);
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setEmail($email);

        $em->persist($user);
        $em->flush();

        $message = sprintf('User %s successfully created', $user->getUsername());
        return $this->json($message);
    }

    public function api()
    {
        $message = sprintf('Logged in as %s', $this->getUser()->getUsername());
        return $this->json($message);
    }

}

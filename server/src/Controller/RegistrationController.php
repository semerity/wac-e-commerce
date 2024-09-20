<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $data = json_decode($request->getContent());
        if (isset($data->email) && isset($data->password)) {
            if (strlen($data->password) >= 8) {
                // encode the plain password
                $user->setEmail($data->email);
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $data->password
                    )
                );
        
                $entityManager->persist($user);
                $entityManager->flush();
        
                // do anything else you need here, like send an email
        
                // return $security->login($user, 'json_login', 'main');
                return $this->json(['success'=>'successfully registered'],200);
            } else {
                return $this->json([
                    'error' => 'Incorrect request 1',
                ],400);
            }
        } else {
            return $this->json([
                'error' => 'Incorrect request 2',
                'data' => $data
            ],400);
        }
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserCrudController extends AbstractController
{
    #[Route('/admin/users', name: 'app_users', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $Userrepo = $entityManager->getRepository(User::class);
        $users = $Userrepo->findAll();

        for ($i = 0; $i < count($users); $i++) {
            $users[$i] = json_decode($serializer->serialize($users[$i], 'json'));
        }

        return $this->json([
            'users' => $users
        ], 200);
    }

    #[Route('/admin/user/{id}', name: 'app_user_get', methods: ['GET'])]
    public function user(EntityManagerInterface $entityManager, $id): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneByid($id);

        return $this->json([
            'method' => 'get',
            'id' => $id,
            'user' => $user
        ], 200);
    }

    #[Route('/admin/user/add', name: 'app_user_add', methods: ['POST'])]
    public function addUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, $id): Response
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
                return $this->json(['success' => 'successfully registered'], 200);
            } else {
                return $this->json([
                    'error' => 'Incorrect request',
                    'data' => $data
                ], 400);
            }
        } else {
            return $this->json([
                'error' => 'Incorrect request : no mail and/or pass',
                'data' => $data
            ], 400);
        }
    }

    #[Route('/admin/user/{id}', name: 'app_user_modify', methods: ['PUT'])]
    public function modifyUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
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
                return $this->json(['success' => 'successfully registered'], 200);
            } else {
                return $this->json([
                    'error' => 'Incorrect request',
                    'data' => $data
                ], 400);
            }
        } else {
            return $this->json([
                'error' => 'Incorrect request : no mail and/or pass',
                'data' => $data
            ], 400);
        }
    }

    #[Route('/user/{id}', name: 'app_user_patch', methods: ['PATCH'])]
    public function changeUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, $id, #[CurrentUser] $curuser = null): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $data = json_decode($request->getContent());

        if (!$curuser) {
            return $this->json(['error' => 'not connected'], 400);
        }
        if (!$user) {
            return $this->json(['error' => 'no user for this id'], 400);
        }
        if (!($curuser == $user)) {
            return $this->json(['error' => 'user connected and user requested is not the same'], 400);
        }

        if ($data === null) {
            return $this->json(['error' => 'Incorrect request'], 400);
        } elseif (!isset($data->email) && !isset($data->password)) {
            return $this->json(['error' => 'Incorrect request'], 400);
        } else {
            if (isset($data->email)) {
                $user->setEmail($data->email);
            }
            if (isset($data->password) && $data->password !== '') {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $data->password
                    )
                );
            }
            $entityManager->flush();

            return $this->json([
                'method' => 'patch',
                'id' => $id,
                'data' => $data,
                'user' => $user
            ], 200);
        }
    }

    #[Route('/admin/user/{id}', name: 'app_user_del', methods: ['DELETE'])]
    public function deleteUser(EntityManagerInterface $entityManager, $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json(['success' => 'successfully registered'], 200);
    }
}

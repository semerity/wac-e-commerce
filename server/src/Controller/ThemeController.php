<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Theme;
use App\Entity\User;

class ThemeController extends AbstractController
{
    #[Route('/theme', name: 'app_theme_get', methods:['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $themeRepository = $entityManager->getRepository(Theme::class);

        // dd($themeRepository->getAllThemes());   

        return $this->json($themeRepository->getAllThemes());
    }

    #[Route('/theme', name: 'app_theme_add', methods:['POST'])]
    public function createTheme(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $request = json_decode($request->getContent());

        $role = $entityManager->getRepository(User::class)->find($request->id_user)->getRoles();

        if (in_array('ROLE_ADMIN', $role)) {
            $theme = new Theme();
            $theme->setTheme($request->name);
            $theme->setColor($request->color);
            $entityManager->persist($theme);
            $entityManager->flush();

            return $this->json('Thème ajouté.');
        }

    }

    #[Route('/theme', name: 'app_theme_delete', methods:['DELETE'])]
    public function deleteTheme(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $request = json_decode($request->getContent());

        $theme = $entityManager->getRepository(Theme::class)->find($request->id_theme);
        $role = $entityManager->getRepository(User::class)->find($request->id_user)->getRoles();

        if (in_array('ROLE_ADMIN', $role)) {
            $entityManager->remove($theme);
            $entityManager->flush();

            return $this->json('Thème supprimé.');
        }
    }

    #[Route('/theme', name: 'app_theme_update', methods:['PATCH'])]
    public function updateTheme(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {   
        $request = json_decode($request->getContent());

        $theme = $entityManager->getRepository(Theme::class)->find($request->id_theme);
        $role = $entityManager->getRepository(User::class)->find($request->id_user)->getRoles();

        if (in_array('ROLE_ADMIN', $role)) {
            $theme->setTheme($request->name);
            $theme->setColor($request->color);
            $entityManager->flush();

            return $this->json([
                'Thème modifié !'
            ]);
        }

        return $this->json('Thème édité.');
    }


    // #[Route('/theme', name: 'app_theme_add'), methods:['POST']]
    // public function addTheme(Request $request, EntityManagerInterface $entityManager): JsonResponse
    // {
    //     $theme = new Theme();
    //     $theme->setTheme();
    //     $theme->setColor(NULL);

    //     return $this->json();
    // }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('base/index.html.twig', [
            'controller_name' => 'BaseController',
            'user' => $user
        ]);

    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response {
        $user = $this->getUser();

        return $this->render('base/profile.html.twig', [
            'controller_name' => 'BaseController',
            'user' => $user
        ]);
    }
}

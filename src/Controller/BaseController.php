<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


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
    public function profile(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder): Response {

        if($this->getUser()) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());

            if($request->request->count() > 0) {

                $newUsername = $request->request->get('_username');
                $newEmail = $request->request->get('_email');
                $password = $request->request->get('_password');
                $password_confirm = $request->request->get('_password_confirm');
                $avatar = $request->files->get('_avatar');

                $change = false;

                if($user->getUsername() != $newUsername && $newUsername != null) {
                    $user->setUsername($newUsername);
                    $change = true;
                }
                if ($user->getEmail() != $newEmail && $newEmail != null) {
                    $user->setEmail($newEmail);
                    $change = true;
                }

                if($password_confirm != null && $password != null && $password_confirm == $password) {
                    $user->setPassword($encoder->encodePassword($user, $password));
                    $change = true;
                }

                if($avatar != null) {
                    $upload_dir = $this->getParameter('upload_directory');
                    $filename = md5(uniqid()).'.'.$avatar->guessExtension();

                    if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);
                    $avatar->move($upload_dir, $filename);
                    $user->setAvatar('images/'.$filename);
                    $change = true;
                }

                if($change) {
                    $manager->persist($user);
                    $manager->flush();
                }

            }

            return $this->render('base/profile.html.twig', [
                'controller_name' => 'BaseController',
                'user' => $user
            ]);
        }
        else return $this->redirect('login');

    }

}

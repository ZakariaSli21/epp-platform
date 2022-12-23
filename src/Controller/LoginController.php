<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/login-parent', name: 'app_login_student')]
    public function login_student(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('login/student-login.html.twig', [
                    'error'         => $error,
        ]);
    }

    #[Route('/login-professor-success', name: 'app_login_professor_success')]
    public function login_professor_succeed_redirection()
    {
        $user = $this->getUser();
        //c'est l'user n'est pas un professeur il aura pas acces a l'espace professeur
        if(in_array('ROLE_PROFESSOR', $user->getRoles()))
        {
            return $this->redirectToRoute('app_home_professor_page');
        }
        else
        {
            return $this->redirectToRoute('app_forbidden');
        }
    }

    #[Route('/login-parent-success', name: 'app_login_student_success')]
    public function login_student_succeed_redirection()
    {
        $user = $this->getUser();
        //c'est l'user n'est pas un parent il aura pas acces a l'espace parent
        if (in_array('ROLE_STUDENT', $user->getRoles()))
        {
            return $this->redirectToRoute('app_home_student_page');
        }
        else
        {
            return $this->redirectToRoute('app_forbidden');
        }
    }

    #[Route('/forbidden', name: 'app_forbidden')]
    public function forbidden()
    {
        return $this->render('forbidden/forbidden.html.twig');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(Security $security): Response
    {
            // logout the user in on the current firewall
            $response = $security->logout();

            // you can also disable the csrf logout
            $response = $security->logout(false);
    }

}

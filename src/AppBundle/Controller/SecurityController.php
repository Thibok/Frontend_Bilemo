<?php

/**
 * Controller for security
 */

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * SecurityController
 */
class SecurityController extends Controller
{
    /**
     * Login
     * @access public
     * @param AuthenticationUtils $authenticationUtils
     * @Route("/", name="bi_login")
     * 
     * @return Response
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        return $this->render('security/login.html.twig', array(
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    /**
     * Logout
     * @access public
     * @Route("/space/logout", name="bi_logout")
     * 
     * @return void
     */
    public function logoutAction() 
    {
    
    }
}
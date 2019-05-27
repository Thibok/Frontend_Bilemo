<?php

/**
 * Controller for client space
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * ClientSpaceController
 */
class ClientSpaceController extends Controller
{
    /**
     * Client space
     * @access public
     * @Route("/space", name="bi_space")
     * @Security("has_role('ROLE_USER')")
     * 
     * @return Response
     */
    public function spaceAction()
    {
        return $this->render('client_space/space.html.twig');
    }
}
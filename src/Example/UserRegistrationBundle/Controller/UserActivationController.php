<?php


namespace Example\UserRegistrationBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class UserActivationController extends Controller
{
    /**
     * @Route("/users/activation/")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function doAction(Request $request)
    {
        $activationKey = $request->query->get('key');

        try {
            $this->get('example.user_registration')->activate($activationKey);
        } catch (\UnexpectedValueException $e) {
            return $this->redirect($this->generateUrl('register'));
        }

        return array();
    }
}
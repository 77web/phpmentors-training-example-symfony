<?php

namespace Example\UserRegistrationBundle\Controller;

use Example\UserRegistrationBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class UserRegistrationController extends Controller
{
    /**
     * @Route("/", name="register")
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createRegistrationForm();

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/do", name="register_do")
     * @Template("ExampleUserRegistrationBundle:UserRegistration:index.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function doAction(Request $request)
    {
        $form = $this->createRegistrationForm();
        $form->bind($request);

        if ($form->isValid()) {
            $user = $form->getData();
            $this->getRegistrationService()->register($user);

            return $this->redirect($this->generateUrl('register_success'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/success", name="register_success")
     * @Template()
     * @return array
     */
    public function successAction()
    {
        return array();
    }

    private function createRegistrationForm()
    {
        return $this->createForm(new UserType());
    }

    private function getRegistrationService()
    {
        return $this->get('example.user_registration');
    }
}

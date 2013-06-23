<?php

namespace Example\UserRegistrationBundle\Controller;

use Example\UserRegistrationBundle\Form\Type\UserRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class UserRegistrationController extends Controller
{
    /**
     * @Route("/users/registration/", name="register")
     * @Method("GET")
     * @Template("ExampleUserRegistrationBundle:UserRegistration:registration_input.html.twig")
     */
    public function inputAction()
    {
        $form = $this->createRegistrationForm();

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/users/registration/", name="register_post")
     * @Method("POST")
     * @Template("ExampleUserRegistrationBundle:UserRegistration:registration_input.html.twig")
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function inputPostAction(Request $request)
    {
        $form = $this->createRegistrationForm();
        $form->bind($request);

        if ($form->isValid())
        {
            $this->get('session')->set('user_data', $form->getData());

            return $this->redirect($this->generateUrl('register_confirm'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/users/registration/confirmation", name="register_confirm")
     * @Method("GET")
     * @Template("ExampleUserRegistrationBundle:UserRegistration:registration_confirmation.html.twig")
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmationAction()
    {
        $user = $this->get('session')->get('user_data');
        if (null === $user) {
            return $this->redirect($this->generateUrl('register'));
        }
        $form = $this->createCSRFForm();

        return array(
            'user' => $user,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/users/registration/confirmation", name="register_do")
     * @Method("POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmationPostAction(Request $request)
    {
        $form = $this->createCSRFForm();
        $form->bind($request);

        $user = $this->get('session')->get('user_data');

        if (!$form->isValid() || null === $user || $request->request->has('prev')) {
            return $this->redirect($this->generateUrl('register'));
        }

        $this->getRegistrationService()->register($user);

        return $this->redirect($this->generateUrl('register_success'));
    }


    /**
     * @Route("/users/registration/success", name="register_success")
     * @Method("GET")
     * @Template("ExampleUserRegistrationBundle:UserRegistration:registration_success.html.twig")
     * @return array
     */
    public function successAction()
    {
        return array();
    }

    private function createRegistrationForm()
    {
        return $this->createForm(new UserRegistrationType());
    }

    private function createCSRFForm()
    {
        return $this->createFormBuilder()->getForm();
    }

    private function getRegistrationService()
    {
        return $this->get('example.user_registration');
    }
}

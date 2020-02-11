<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegisterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\Form\Form;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="indexas")
     */
    public function index()
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        // ...

        $form = $this->createForm(UserRegisterFormType::class, $user);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            $postData = $request->getContent();
            $data = [];
            parse_str(html_entity_decode($postData), $data);

            $form->submit($data[$form->getName()]);
            if ($form->isSubmitted() && $form->isValid()) {

                // perform some action...
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                // 4) save the User!
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return new JsonResponse(array(
                    'status' => 'OK',
                    'message' => 1),
                    200);
            } else {

                return new JsonResponse(array(
                    'status' => 'error',
                    'message' => 'Invalid form',
                    'data' => $this->getErrorMessages($form)
                ),
                    200);
            }
        }

        return $this->render('users/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('users/login.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @param Form $form
     * @return array
     */
    protected function getErrorMessages(Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Doctrine\Bundle\FixturesBundle\Fixture;


class AuthenticatorController extends AbstractController
{
    /**
     * @Route("/authenticator", name="authenticator")
     */
    public function index()
    {
        return $this->render('authenticator/index.html.twig', [
            'controller_name' => 'AuthenticatorController',
        ]);
    }

    /**
    * @Route("/login", name="login")
    */

   public function auth(Request $request)

   {
       $data = array(

           // you might translate this message

           'message' => 'Authentication Required'

       );

       return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);

   }

    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */
    public function register(Request $request,UserPasswordEncoderInterface $encoder)
    {


        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();

        $user->setUsername($request->request->get('username'));
        $user->setEmail($request->request->get('email'));
        $encoded = $encoder->encodePassword($user, $request->request->get('password'));
        $user->setPassword($encoded);

        $user->setRoles('0');
        $user->setApiToken($request->request->get('api_token'));


        $entityManager->persist($user);
        $entityManager->flush();




        /** @var TYPE_NAME $data */
        return new JsonResponse($user, Response::HTTP_UNAUTHORIZED);
    }
}
<?php
namespace App\Security;



use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AuthenticatorInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator

{
    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser(). Returning null will cause this authenticator
     * to be skipped.
     * @param Request $request
     * @return array
     */

   public function getCredentials(Request $request)

   {
       if (!$token = $request->headers->get('X-AUTH-TOKEN')) {

           // No token?

           $token = null;

       }

       // What you return here will be passed to getUser() as $credentials

       return array(

           'token' => $token,

       );

   }

   public function getUser($credentials, UserProviderInterface $userProvider)

   {
       $apikey = $credentials['token'];

       if (null === $apikey) {

           return;

       }
       // if null, authentication will fail

       // if a User object, checkCredentials() is called

       return $userProvider->loadUserByUsername($apikey);

   }

   public function checkCredentials($credentials, UserInterface $user)

   {
       // check credentials - e.g. make sure the password is valid

       // no credential check is needed in this case



       // return true to cause authentication success

       return true;

   }

   public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)

   {

       // on success, let the request continue

       return null;

   }

   public function onAuthenticationFailure(Request $request, AuthenticationException $exception)

   {

       $data = array(

           'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

           // or to translate this message

           // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())

       );

       return new JsonResponse($data, Response::HTTP_FORBIDDEN);

   }

   public function supportsRememberMe()

   {
       return false;

   }

    /**
     * Does the authenticator support the given Request?
     *
     * If this returns false, the authenticator will be skipped.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        // TODO: Implement supports() method.
    }

    /**
     * Returns a response that directs the user to authenticate.
     *
     * This is called when an anonymous request accesses a resource that
     * requires authentication. The job of this method is to return some
     * response that "helps" the user start into the authentication process.
     *
     * Examples:
     *
     * - For a form login, you might redirect to the login page
     *
     *     return new RedirectResponse('/login');
     *
     * - For an API token authentication system, you return a 401 response
     *
     *     return new Response('Auth header required', 401);
     *
     * @param Request $request The request that resulted in an AuthenticationException
     * @param AuthenticationException $authException The exception that started the authentication process
     *
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array (
        // tu pourrais traduire ce message
        'message' => 'Authentification requise'
        );

        return new JsonResponse ( $data, Response::HTTP_UNAUTHORIZED);
    }
}
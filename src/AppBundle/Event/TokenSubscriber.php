<?php

namespace AppBundle\Event;

use AppBundle\Controller\Api\ApiControllerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;

class TokenSubscriber implements EventSubscriberInterface
{
    private $tokens;

    public function __construct($tokens = [])
    {
        $this->tokens = $tokens;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }


        if ($controller[0] instanceof ApiControllerInterface) {

            $headers = $event->getRequest()->headers->all();




            if (!isset($headers['x-api-request'])) {
                
                throw new AccessDeniedHttpException('Authentication Required');
                // return new JsonResponse([
                //     'success' => false,
                //     'error' => 'Authentication Required'
                // ]);
            }

            $token = $headers['x-api-request'][0];


            $decodedJwt = \Lindelius\JWT\JWT::decode($token);

            /**
             * You can access the claims as soon as you have decoded the JWT.
             * But, NEVER trust the JWT until you have verified it, though!
             */
            $isAdmin = (bool) $decodedJwt->admin;

            if (!$decodedJwt->verify($this->tokens[0])) {
                
                throw new AccessDeniedHttpException('Authentication Required');
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => 'onKernelController',
        );
    }
}
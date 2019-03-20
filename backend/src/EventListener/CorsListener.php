<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CorsListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST  => array('onKernelRequest', 9999),
            KernelEvents::RESPONSE => array('onKernelResponse', 9999),
        );
    }

    public function onKernelRequest(GetResponseEvent $event)
{
    // Don't do anything if it's not the master request.
    if (!$event->isMasterRequest()) {
        return;
    }
    $request = $event->getRequest();
    $method  = $request->getRealMethod();
    if ('OPTIONS' == $method) {
        $response = new Response();
        $event->setResponse($response);
    }
}
    public function onKernelResponse(FilterResponseEvent $event)
{
    // Don't do anything if it's not the master request.
    if (!$event->isMasterRequest()) {
        return;
    }
    $response = $event->getResponse();
    $response->headers->set('Access-Control-Allow-Origin', '*');
    $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT');
    $response->headers->set('Access-Control-Allow-Headers', 'X-Header-One,X-Header-Two');
}

}

?>
<?php

namespace Infrastructure\Event;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;

class RedirectAuthenticatedUserListener
{
    public function __construct(
        private Security $security,
        private RouterInterface $router
    ) {}

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $authRoutes = [
            'app_security',
            'app_forgot_password', 
            'app_reset_password'
        ];
        $currentRoute = $request->attributes->get('_route');
        if (in_array($currentRoute, $authRoutes) && $this->security->getUser()) {
            $event->setResponse(
                new RedirectResponse(
                    $this->router->generate('app_dashboard')
                )
            );
        }
    }
}

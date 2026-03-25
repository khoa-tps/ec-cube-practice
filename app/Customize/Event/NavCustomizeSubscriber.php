<?php

namespace Customize\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class NavCustomizeSubscriber implements EventSubscriberInterface
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 5]],
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $globals = $this->twig->getGlobals();
        if (isset($globals['eccubeNav'])) {
            $eccubeNav = $globals['eccubeNav'];
            if (isset($eccubeNav['order']['children']['plugin_coupon'])) {
                unset($eccubeNav['order']['children']['plugin_coupon']);
            }
            $this->twig->addGlobal('eccubeNav', $eccubeNav);
        }
    }
}

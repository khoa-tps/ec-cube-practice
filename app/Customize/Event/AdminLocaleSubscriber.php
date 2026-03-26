<?php

namespace Customize\Event;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AdminLocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 50]],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $adminRoute = (string) $this->params->get('eccube_admin_route');
        $pathInfo = (string) $request->getPathInfo();

        if (strpos($pathInfo, '/'.$adminRoute) !== 0) {
            return;
        }

        $session = $request->getSession();
        if (!$session) {
            return;
        }

        $locale = (string) $session->get('eccube.admin.locale', '');
        if ($locale === '') {
            return;
        }

        $allowed = (string) ($this->params->has('app_locales') ? $this->params->get('app_locales') : '');
        $allowedLocales = array_values(array_filter(array_map('trim', preg_split('/[|,\\s]+/', $allowed) ?: [])));

        if ($allowedLocales && !in_array($locale, $allowedLocales, true)) {
            return;
        }

        $request->setLocale($locale);
    }
}


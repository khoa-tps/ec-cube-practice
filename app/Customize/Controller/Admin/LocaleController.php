<?php

namespace Customize\Controller\Admin;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class LocaleController
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @Route(
     *     "/%eccube_admin_route%/locale/{_locale}",
     *     name="admin_locale_switch",
     *     requirements={"_locale"="[A-Za-z_\\-]+"},
     *     methods={"GET"}
     * )
     */
    public function switch(Request $request, string $_locale): RedirectResponse
    {
        $allowed = (string) ($this->params->has('app_locales') ? $this->params->get('app_locales') : '');
        $allowedLocales = array_values(array_filter(array_map('trim', preg_split('/[|,\\s]+/', $allowed) ?: [])));

        if ($allowedLocales && !in_array($_locale, $allowedLocales, true)) {
            $_locale = (string) $this->params->get('locale');
        }

        $session = $request->getSession();
        if ($session) {
            $session->set('eccube.admin.locale', $_locale);
        }

        $referer = (string) $request->headers->get('referer', '');
        if ($referer !== '') {
            return new RedirectResponse($referer);
        }

        return new RedirectResponse($request->getBaseUrl().'/'.$this->params->get('eccube_admin_route').'/');
    }
}


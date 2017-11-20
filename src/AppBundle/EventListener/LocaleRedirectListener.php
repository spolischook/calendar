<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class LocaleRedirectListener
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var array
     */
    private $supportedLocales;

    /**
     * @var string
     */
    private $localeRouteParam;

    public function __construct(RouterInterface $router, $defaultLocale = 'en', array $supportedLocales = ['en'], $localeRouteParam = '_locale')
    {
        $this->router = $router;
        $this->routeCollection = $router->getRouteCollection();
        $this->defaultLocale = $defaultLocale;
        $this->supportedLocales = $supportedLocales;
        $this->localeRouteParam = $localeRouteParam;
    }

    public function isLocaleSupported($locale)
    {
        return in_array($locale, $this->supportedLocales);
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        //GOAL:
        // Redirect all incoming requests to their /locale/route equivlent as long as the route will exists when we do so.
        // Do nothing if it already has /locale/ in the route to prevent redirect loops

        $request = $event->getRequest();
        $path = $request->getPathInfo();

        foreach($this->routeCollection as $routeName => $routeObject){
            $routePath = $routeObject->getPath();
            if($routePath == "/{_locale}".$path){
                break;
            }
            unset($routeName);
        }

        //If the route does indeed exist then lets redirect there.
        if(isset($routeName)){
            //Get the locale from the users browser.
            $locale = $request->getPreferredLanguage();

            //If no locale from browser or locale not in list of known locales supported then set to defaultLocale set in config.yml
            if($locale==""  || $this->isLocaleSupported($locale)==false){
                $locale = $request->getDefaultLocale();
            }

            $event->setResponse(new RedirectResponse($this->router->generate(
                $routeName,
                array_merge($request->attributes->all(), ['_locale' => $locale])
            )));
        }

        //Otherwise do nothing and continue on~
    }

    /**
     * Took from https://gist.github.com/spolischook/0cde9c6286415cddc088
     * @param Request $request
     * @return array
     */
    private function getPreferredLocale(Request $request)
    {
        $prefLocales = array_reduce(
            explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']),
            function ($res, $el) {
                list($l, $q) = array_merge(explode(';q=', $el), [1]);
                $res[$l] = (float) $q;
                return $res;
            }, []);
        arsort($prefLocales);

        return $prefLocales;
    }
}

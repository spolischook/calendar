<?php

namespace Tests\AppBundle\EventListener;

use AppBundle\EventListener\LocaleRedirectListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class LocaleRedirectListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider responseEventProvider
     * @param GetResponseEvent $event
     */
    public function testOnKernelRequest(GetResponseEvent $event, $expectedLocale)
    {
        $listener = new LocaleRedirectListener();
        $listener->onKernelRequest($event);

        /** @var RedirectResponse $response */
        $response = $event->getResponse();
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame($response->getTargetUrl(), '/'.$expectedLocale);
    }

    public function responseEventProvider()
    {
        return [
            'Master Request & en' => [
                'request' => $this->getEventMock(
                    ['HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.8,uk;q=0.6,ru;q=0.4'],
                    HttpKernelInterface::MASTER_REQUEST
                ),
                'expected_locale' => 'en'
            ],
            'Sub Request & en' => [
                'request' => $this->getEventMock(
                    ['HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.8,uk;q=0.6,ru;q=0.4'],
                    HttpKernelInterface::SUB_REQUEST
                ),
                'expected_locale' => 'en'
            ],
            'Master Request & uk' => [
                'request' => $this->getEventMock(
                    ['HTTP_ACCEPT_LANGUAGE' => 'uk-UA,uk;q=0.8,en;q=0.6,ru;q=0.4'],
                    HttpKernelInterface::MASTER_REQUEST
                ),
                'expected_locale' => 'uk'
            ],
            'Sub Request & uk' => [
                'request' => $this->getEventMock(
                    ['HTTP_ACCEPT_LANGUAGE' => 'uk-UA,uk;q=0.8,en;q=0.6,ru;q=0.4'],
                    HttpKernelInterface::SUB_REQUEST
                ),
                'expected_locale' => 'uk'
            ],
            'Master Request & not supported => default locale' => [
                'request' => $this->getEventMock(
                    ['HTTP_ACCEPT_LANGUAGE' => 'ru-RU,ru;q=0.8'],
                    HttpKernelInterface::MASTER_REQUEST
                ),
                'expected_locale' => 'en'
            ],
            'Sub Request & not supported => default locale' => [
                'request' => $this->getEventMock(
                    ['HTTP_ACCEPT_LANGUAGE' => 'ru-RU,ru;q=0.8'],
                    HttpKernelInterface::SUB_REQUEST
                ),
                'expected_locale' => 'en'
            ],
        ];
    }

    /**
     * @param array $server
     * @param string $requestType
     * @return GetResponseEvent
     */
    public function getEventMock(array $server, $requestType)
    {
        /** @var HttpKernelInterface $kernelMock */
        $kernelMock = $this->getMock(HttpKernelInterface::class);
        $request = new Request([], [], [], [], [], $server);
        return new GetResponseEvent(
            $kernelMock,
            $request,
            $requestType
        );
    }
}

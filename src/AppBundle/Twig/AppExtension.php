<?php

namespace AppBundle\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    /**
     * @var string
     */
    private $canonicalUrl;

    public function __construct(UrlGeneratorInterface $generator, $canonicalUrl)
    {
        $this->generator = $generator;
        $this->canonicalUrl = $canonicalUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('canonical_url', [$this, 'getUrl']),
            new TwigFunction('is_canonical', [$this, 'isCanonical']),
        ];
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isCanonical(Request $request)
    {
        $canonicalHost = parse_url($this->canonicalUrl, PHP_URL_HOST);
        $currentHost = $request->getHost();

        return $currentHost === $canonicalHost;
    }

    /**
     * @param string $name
     * @param array  $parameters
     *
     * @return string
     */
    public function getUrl($name, $parameters = [])
    {
        $canonicalUrl = $this->canonicalUrl. $this->generator->generate(
            $name,
            $parameters,
            UrlGeneratorInterface::ABSOLUTE_PATH
        );

        return str_replace('app_dev.php/', '', $canonicalUrl);
    }
}

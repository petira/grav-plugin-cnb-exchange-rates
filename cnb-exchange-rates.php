<?php
namespace Grav\Plugin;
use Grav\Common\Plugin;
class CNBExchangeRatesPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => [
                ['autoload', 100000],
                ['onPluginsInitialized', 0]
            ]
        ];
    }
    public function autoload()
    {
        require_once(__DIR__ . '/classes/CNBTwigExtension.php');
    }
    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            return;
        }
        $this->enable([
            'onTwigExtensions' => [
                ['onTwigExtensions', 0]
            ]
        ]);
    }
    public function onTwigExtensions()
    {
        $this->grav['twig']->twig->addExtension(new CNBTwigExtension());
    }
}

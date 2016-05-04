<?php

namespace Laasti\Views\Providers;

class LeagueViewsProvider extends \League\Container\ServiceProvider\AbstractServiceProvider implements \League\Container\ServiceProvider\BootableServiceProviderInterface
{

    protected $provides = [
        'Laasti\Views\Data\LazyData',
        'Laasti\Views\Data\ArrayData',
        'Laasti\Views\Data\DataInterface',
        'Laasti\Views\Engines\Mustache',
        'Laasti\Views\Engines\PlainPhp',
        'Laasti\Views\Template',
        'Laasti\Views\TemplateRenderer',
        'Laasti\Views\TemplateStream'
    ];
    protected $defaultConfig = [
        'engine_class' => 'Laasti\Views\Engines\Mustache',
        'data_class' => 'Laasti\Views\Data\ArrayData',
        'data' => []
    ];

    public function register()
    {
        $config = $this->getConfig();
        $this->getContainer()->add('Laasti\Views\Data\ArrayData');
        $arguments = [$config['data']];
        if ($config['data_class'] === 'Laasti\Views\Data\LazyData') {
            $arguments[] = 'Laasti\Lazydata\Resolvers\ResolverInterface';
        }
        $this->getContainer()->add('Laasti\Views\Data\DataInterface', $config['data_class'])->withArguments($arguments);
        if (class_exists('Mustache_Engine')) {
            if (!$this->getContainer()->has('Mustache_Engine')) {
                $this->getContainer()->add('Mustache_Engine');
            }
            $this->getContainer()->add('Laasti\Views\Engines\Mustache', function($engine, $locations, $partials) {
                $loaders = [];
                foreach ($partials as $partial_location) {
                    $loaders[] = new \Mustache_Loader_FilesystemLoader($partial_location);
                }
                $engine->setPartialsLoader(new \Mustache_Loader_CascadingLoader($loaders));
                return new \Laasti\Views\Engines\Mustache($engine, $locations);
            })->withArguments([
                'Mustache_Engine', $this->getContainer()->get('config')['views']['locations'], $this->getContainer()->get('config')['views']['partial_locations']
            ]);
        }
        $this->getContainer()->add('Laasti\Views\Engines\TemplateEngineInterface', function($engine) {
            return $engine;
        })->withArgument($config['engine_class']);
        $this->getContainer()->add('Laasti\Views\Template');
        $this->getContainer()->add('Laasti\Views\TemplateStream')->withArguments([
            'Laasti\Views\TemplateRender'
        ]);
        $this->getContainer()->add('Laasti\Views\TemplateRender')->withArguments([
            'Laasti\Views\Engines\TemplateEngineInterface',
            'Laasti\Views\Template'
        ]);
        $this->getContainer()->share('Laasti\Views\TemplateRenderer')->withArguments([
            'Laasti\Views\Data\DataInterface'
        ])->withMethodCall('addEngine', ['Laasti\Views\Engines\TemplateEngineInterface']);
    }

    public function boot()
    {
        $this->getContainer()->inflector('Laasti\Views\TemplateRendererAwareInterface')
                ->invokeMethod('setTemplateRenderer', ['Laasti\Views\TemplateRenderer']);
    }

    protected function getConfig()
    {
        $config = $this->getContainer()->get('config');

        if (isset($config['views'])) {
            return array_merge($this->defaultConfig, $config['views']);
        }

        return $this->defaultConfig;
    }

}

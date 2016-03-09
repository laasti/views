<?php

namespace Laasti\Views\Engines;

use Laasti\Views\Engines\TemplateEngineInterface;
use Laasti\Views\Exceptions\TemplateNotFoundException;

/**
 * A Mustache template engine wrapper for mustache/mustache
 */
class Mustache implements TemplateEngineInterface
{

    protected $mustache;
    protected $extension;

    /**
     * Constructor
     * @param array $locations
     */
    public function __construct(\Mustache_Engine $mustache, $locations = [], $extension = 'mustache')
    {
        $this->mustache = $mustache;
        if (count($locations)) {
            $loaders = [];
            foreach ($locations as $location) {
                $loaders[] = new \Mustache_Loader_FilesystemLoader($location);
            }
            $this->mustache->setLoader(new \Mustache_Loader_CascadingLoader($loaders));
        }
        $this->extension = $extension;
    }

    /**
     * Adds a new folder to look for templates
     * @param string $location
     * @return PlainPhp
     */
    public function addLocation($location)
    {
        $loader = new \Mustache_Loader_FilesystemLoader($location);
        if ($this->mustache->getLoader() instanceof \Mustache_Loader_CascadingLoader) {
            $this->mustache->getLoader()->addLoader( $loader);
        } else {
            $loaders = [$this->mustache->getLoader(), $loader];
            $this->mustache->setLoader(new \Mustache_Loader_CascadingLoader($loaders));
        }
        return $this;
    }

    /**
     * Renders a template, the first level of data is extracted as variables
     * @param string $file
     * @param array $data
     * @return type
     */
    public function render(\Laasti\Views\Template $template)
    {
        return $this->mustache->render($template->getView(), $template->getData());
    }

    /**
     *
     * @param \Laasti\Views\Template $template
     * @return bool
     */
    public function canRender(\Laasti\Views\Template $template)
    {
        return pathinfo($template->getView(), PATHINFO_EXTENSION) === $this->getExtension();
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * Finds the file by looping through locations
     * @param string $file
     * @return string
     * @throws TemplateNotFoundException
     */
    protected function findTemplateFile($file)
    {
        //Add missing extension
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $file = ($ext === '') ? $file . '.'.$this->getExtension() : $file;

        $found = false;
        foreach ($this->locations as $location) {
            if (is_file($location . '/' . $file)) {
                $found = true;
                $file = $location . '/' . $file;
                continue;
            }
        }

        if (!$found) {
            throw new TemplateNotFoundException('This template was not found in any of the registered locations: ' . $file);
        }

        return $file;
    }

}

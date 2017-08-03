<?php

namespace Laasti\Views;

use Laasti\Views\Data\DataInterface;
use Laasti\Views\Engines\TemplateEngineInterface;
use Laasti\Views\Exceptions\TemplateNotFoundException;
use Psr\Http\Message\ResponseInterface;

class TemplateRenderer
{
    protected $engines = [];
    protected $data = [];

    public function __construct(DataInterface $data = null)
    {
        $this->data = $data ?: new \Laasti\Views\Data\ArrayData;
    }

    public function addEngine(TemplateEngineInterface $engine)
    {
        $this->engines[] = $engine;
        return $this;
    }

    public function render(Template $template)
    {
        return $this->getEngineForTemplate($template)->render($template);
    }

    public function getEngineForTemplate(Template $template)
    {
        foreach ($this->engines as $engine) {
            if ($engine->canRender($template)) {
                return $engine;
            }
        }
        throw new TemplateNotFoundException('The template could not be rendered by any engine: ' . $template->getView());
    }

    public function renderView($view, DataInterface $data = null)
    {
        if (is_null($data)) {
            $template = new Template($view, $this->data);
        } else {
            $template = new Template($view, $this->data->withData($data));
        }
        return $this->getEngineForTemplate($template)->render($template);
    }

    public function attachStream(ResponseInterface $response, Template $template)
    {
        $template = $template->withData($this->data->withData($template->getData()));
        return $response->withBody(new TemplateStream(new TemplateRender($this->getEngineForTemplate($template),
            $template)));
    }

    public function getData($property, $default = null)
    {
        return $this->data->get($property, $default);
    }

    public function setData($property, $value)
    {
        $this->data->set($property, $value);
        return $this;
    }

    public function hasData($property)
    {
        return $this->data->has($property);
    }

    public function removeData($property)
    {
        $this->data->remove($property);
        return $this;
    }

    public function addData($data)
    {
        $this->data->add($data);
        return $this;
    }

    public function pushData($property, $value)
    {
        $this->data->push($property, $value);
        return $this;
    }

    public function exportData()
    {
        return $this->data->export();
    }

    public function clearData()
    {
        return $this->data->clear();
    }
}

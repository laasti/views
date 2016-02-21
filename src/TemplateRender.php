<?php


namespace Laasti\Views;

class TemplateRender
{
    /**
     *
     * @var Engines\TemplateEngineInterface
     */
    protected $engine;

    /**
     *
     * @var Template
     */
    protected $template;

    /**
     *
     * @var ArrayAccess
     */
    protected $data;

    public function __construct(Engines\TemplateEngineInterface $engine, Template $template)
    {
        $this->engine = $engine;
        $this->template = $template;
    }

    public function getEngine()
    {
        return $this->engine;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function withEngine(Engines\TemplateEngineInterface $engine)
    {
        $new = clone $this;
        $new->engine = $engine;
        return $new;
    }

    public function withTemplate(Template $template)
    {
        $new = clone $this;
        $new->template = $template;
        return $new;
    }

    public function render()
    {
        return $this->engine->render($this->template);
    }



}

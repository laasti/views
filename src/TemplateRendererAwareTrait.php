<?php

namespace Laasti\Views;

trait TemplateRendererAwareTrait
{
    /**
     *
     * @var \Laasti\Views\TemplateRenderer 
     */
    protected $templateRenderer;

    /**
     * 
     * @param \Laasti\Views\TemplateRenderer $templateRenderer
     */
    public function setTemplateRenderer(TemplateRenderer $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
        return $this;
    }

    /**
     *
     * @return \Laasti\Views\TemplateRenderer
     */
    public function getTemplateRenderer()
    {
        return $this->templateRenderer;
    }
}

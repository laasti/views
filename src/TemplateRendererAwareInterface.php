<?php

namespace Laasti\Views;

interface TemplateRendererAwareInterface
{
    /**
     *
     * @param \Laasti\Views\TemplateRenderer $templateRenderer
     */
    public function setTemplateRenderer(TemplateRenderer $templateRenderer);

    /**
     *
     * @return \Laasti\Views\TemplateRenderer
     */
    public function getTemplateRenderer();
}

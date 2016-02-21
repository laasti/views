<?php

namespace Laasti\Views\Engines;

/**
 * Interface for template engines
 */
interface TemplateEngineInterface
{

    /**
     * Renders the template file using the data
     * @param \Laasti\Views\Template $template
     * @return string
     */
    public function render(\Laasti\Views\Template $template);

    /**
     * Returns whether or not the template can be rendered by the engine
     * @param \Laasti\Views\Template $template
     * @return bool
     */
    public function canRender(\Laasti\Views\Template $template);
}

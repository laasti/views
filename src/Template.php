<?php

namespace Laasti\Views;

class Template
{
    protected $view;
    protected $data;

    public function __construct($view, \ArrayAccess $data = null)
    {
        $this->view = $view;
        $this->data = $data;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getData()
    {
        return $this->data ?: new Data\ArrayData;
    }

    public function withView($view)
    {
        $new = clone $this;
        $new->view = $view;
        return $new;
    }

    public function withData(\ArrayAccess $data)
    {
        $new = clone $this;
        $new->data = $data;
        return $new;
    }


}

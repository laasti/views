<?php

namespace Laasti\Views\Data;

/**
 * ArrayData Class
 *
 */
class LazyData extends \Laasti\Lazydata\Data implements DataInterface
{
    
    public function clear()
    {
        $this->data = [];
        
        return $this;
    }
    
    public function has($property)
    {
        $rand = uniqid('lazydata', true);

        return $this->get($property, $rand) !== $rand;
    }
    
    public function withData(DataInterface $data)
    {
        $new = clone $this;
        $new->add($data->export());
        return $new;
    }
}

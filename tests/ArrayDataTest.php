<?php

namespace Laasti\Views\Tests;

class ArrayDataTest extends \PHPUnit_Framework_TestCase
{

    public function testProvider()
    {
        $data = new \Laasti\Views\Data\ArrayData();
        $data->set('test', 2);
        $this->assertTrue($data->has('test'));
        $this->assertTrue($data->get('test') === 2);
        $this->assertTrue($data->get('test', 5) === 2);
        $this->assertTrue($data->get('test2', 5) === 5);
    }

}

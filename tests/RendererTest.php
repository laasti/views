<?php

namespace Laasti\Response\Test;

use Laasti\Views\Data\ArrayData;
use Laasti\Views\Engines\PlainPhp;
use Laasti\Views\Template;
use Laasti\Views\TemplateRenderer;
use PHPUnit_Framework_TestCase;

class RendererTest extends PHPUnit_Framework_TestCase
{

    public function testPlainPhpEngine()
    {
        $data = new ArrayData;
        $engine = new PlainPhp([__DIR__]);
        $renderer = new TemplateRenderer($data);
        $renderer->addEngine($engine);
        $renderer->setData('page.title', 'My title');

        $response = $renderer->renderView('plainphp-template.php');
        $this->assertEquals($response, 'My title');

    }

    public function testTemplateLocations()
    {
        $engine = new PlainPhp([__DIR__]);

        $engine->addLocation('/append-folder');
        $engine->prependLocation('/prepend-folder');

        $locations = \PHPUnit_Framework_Assert::readAttribute($engine, 'locations');

        $this->assertEquals('/append-folder', $locations[2]);
        $this->assertEquals('/prepend-folder', $locations[0]);

    }

    public function testNotFound()
    {
        $engine = new PlainPhp([__DIR__]);
        $this->setExpectedException("Laasti\Views\Exceptions\TemplateNotFoundException");
        $this->assertEquals('Test title', $engine->render(new Template('plainphp-template', new ArrayData(['page' => ['title' => 'Test title']]))));
        $engine->render(new Template('template-notfound'));
        
    }

    public function testTemplateEngineAssigment()
    {
        $data = new ArrayData;
        $engine = new PlainPhp([__DIR__]);
        $renderer = new TemplateRenderer($data);
        $renderer->addEngine($engine);
        $engine2 = new PlainPhp([__DIR__], 'php2');
        $template = new Template('test.php');
        $this->assertTrue($renderer->getEngineForTemplate($template) === $engine);
        $template2 = new Template('test.php2');
        $renderer->addEngine($engine2);
        $this->assertTrue($renderer->getEngineForTemplate($template2) === $engine2);

    }

    public function testRendererData()
    {
        $data = new ArrayData;
        $engine = new PlainPhp([__DIR__]);
        $renderer = new TemplateRenderer($data);
        $renderer->addEngine($engine);
        $renderer->setData('first', 1);
        $renderer->addData(['batch' => 2, 'another' => 3]);
        $this->assertTrue(1 == $renderer->getData('first'));
        $this->assertTrue(['first'=> 1, 'batch' => 2, 'another' => 3] == $renderer->exportData());
        $renderer->setData('first', 4);
        $this->assertTrue($renderer->getData('first') === 4);
        $this->assertTrue($renderer->getData('notexist') === null);
        $this->assertTrue($renderer->getData('notexist', 4) === 4);
        $renderer->removeData('first');
        $this->assertTrue(['batch' => 2, 'another' => 3] == $renderer->exportData());
        $renderer->clearData();
        $this->assertTrue(count($renderer->exportData()) === 0);
        $renderer->setData('push', ['test']);
        $renderer->pushData('push', 'test2');
        $this->assertTrue(['test', 'test2'] == $renderer->getData('push'));
    }

    public function testViewTemplateEngine()
    {
        $data = new ArrayData;
        $engine = new PlainPhp([__DIR__]);
        $renderer = new TemplateRenderer($data);
        $renderer->addEngine($engine);
        $template = new Template('plainphp-template.php', new ArrayData(['page' => ['title' => 'Test']]));
        $response = $renderer->attachStream(new \Zend\Diactoros\Response(), $template);
        $this->assertTrue($response->getBody()->getContents() === 'Test');
    }

}

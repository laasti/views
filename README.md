# Laasti/views

A HTTP Message compatible template engine abstraction. Provides a TemplateStream that can be attached to HTTP Message's Response.

Only support PHP and Mustache (bobthecow/mustache.php) templates for the moment, PRs are welcome to add more engines.

## Installation

```
composer require laasti/views
```

## Usage

```php
$renderer = new \Laasti\Views\TemplateRenderer;
//You can add many engines to support multiple template types
$renderer->addEngine(new \Laasti\Views\Engines\PlainPhp([/*View directory*/]));
//You can set global data to pass on to all template
$renderer->setData('sitename', 'Hello world!');
//Or you can pass data only to one template
$template = new \Laasti\Views\Template("template-name.php", new \Laasti\Views\Data\ArrayData(['title' => 'Hello world!']));
//attackStream will create a TemplateStream and attach it to the response's body
$response = $renderer->attachStream($response, $template);

//The title and sitename will be available as $title and $sitename in the template

```


## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## History

See Github's releases or tags

## Credits

Author: Sonia Marquette (@nebulousGirl)

## License

Released under the MIT License. See LICENSE.txt file.
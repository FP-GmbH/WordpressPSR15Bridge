# WordpressPSR15Bridge
This PSR-15 middleware allows you to include Wordpress into your Middleware-Pipeline

## Getting Started

### Prerequisites

* Install Wordpress within a public accesible directory of your webserver.
* install composer if you haven't yet (seriously [install composer](https://getcomposer.org/)) 
* TemplateRendererInterface Implementation to pass into WordpressAction


### Installing

Install Composer
```
$ composer require fundp/wordpress-psr15-bridge
```

Generate `WordpressAction` with a factory. Inject `TemplateRendererInterface`-Implementation, the `WordpressBridgeService` within this lib and

```php
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $template = $container->get(TemplateRendererInterface::class);
        $wordpressBridgeService = $container->get(WordpressBridgeService::class);
        return new WordpressAction($template, $wordpressBridgeService, 'yourproject::your-template');
    }
```

pipe Action into your pipeline

```php
$app->pipe( WordpressAction::class);
```

register action and your factory in your dependency injection. May look like this with [Zendframework](https://github.com/zendframework/zend-servicemanager)

```php
return [
   'factories'  => [
      WordpressAction::class => WordpressActionFactory::class
   ]
];
```

Create a TemplateFile that contains the necessary Template-Variable like this

blank-wordpress.phtml
```
<?= $this->wordpress_string?>
```


## Running the tests

`$ Run, Forrest, run!`

## Built With

* [http-interop/http-middleware](https://github.com/http-interop/http-middleware)
* [zendframework/zend-diactoros](https://github.com/zendframework/zend-diactoros)
* [composer](https://getcomposer.org/)
* 

## Contributing

Workflow for contributing is not defined yet which leaves all the opportunities to you by now. 

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Bastian Charlet** - *Initial work* - [Bastianowicz](https://github.com/Bastianowicz)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Inspired by legacy integration approach of [RalfEggert](https://github.com/RalfEggert)

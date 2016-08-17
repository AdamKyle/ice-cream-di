
# Ice Cream DI

[![Build Status](https://travis-ci.org/AdamKyle/ice-cream-di.svg?branch=master)](https://travis-ci.org/AdamKyle/ice-cream-di)
[![Packagist](https://img.shields.io/packagist/v/ice-cream/di.svg?style=flat)](https://packagist.org/packages/ice-cream/di)
[![Maintenance](https://img.shields.io/maintenance/yes/2016.svg)]()
[![Made With Love](https://img.shields.io/badge/Made%20With-Love-green.svg)]()

Requires PHP 7.0+

I wanted a simple and effective way to replicate [pimple](http://pimple.sensiolabs.org/#modifying-services-after-definition) and create DI at it's most basic level.

The core concept is simple, you have a container that you can add items to and fetch via the [ArrayAccess](http://php.net/manual/en/class.arrayaccess.php) interface.

We also allow you to create factories, which allow you to return new instances of the object each time instead of the same object every time.

## Philosophy

Ice Cream is not meant to be the next big thing in OSS. It is essentially Pimple plagiarized.

I wanted to build a simple DI container that I could use in my own projects to try and better understand DI at a fundamental level.

## Examples:

The following is a super basic example of how to use the container.

```php
use IceCreamDI\Container;

$container = new Container();

$container['service'] = function($c) {
  return new Service();
}

$container['services'] // Returns instance of Service class.
```

> ATTN!!
>
> We pass the instance of the container to all of our closures, this is exactly like pimple.
> Even when it comes to extending the already registered service provider you can be sure
> that the new extension will also have the container object.

Even more simpler:

```php
$container = new Container([
  'app.service' => function ($c) {
    return new Service();
  },
]);
```

> ATTN!
>
> Should you have something in the container with the same name and you throw in another object under the same name you will break your container. Make sure your names are unique.

> ATTN!
>
>You cannot manipulate a container object after its been called, for example:
>
> ```php
> $container = new Container();
> $container['service'] = function ($c) { return new Service(); };
>
> $service = $container['service'];
>
> $container->extend('service', function($service){ $service->someMethodCall(); });
>```
>
> The above will error out, because when you "build" the item from the service container, we lock it in place.

What if you want to extend an already registered item in the container? Well we can do the following:

```php
use IceCreamDI\Container;

$container = Container();

$container['service'] = function($c) {
  return new Service();
}

$container->extend('service', function($service){
  $service-> ....

  ....

  return $service;
});
```

This allows you to easily register and then manipulate items in the container.

If you just want the closure back, you can call the raw method:

```php
use IceCreamDI\Container;

$container = Container();

$container['service'] = function($c) {
  return new Service();
}

$container->raw('service'); // => closure instance.
```

When you call the factory method, you will **always** get a new instance of the registered object. For instance:

```php
use IceCreamDI\Container;

$container = Container();

$container['service'] = $container->factory(function($c) {
  return new $service();
});

$container['service']; // => Will be a new instance every time.
```

Where as with the the regular `$container['service']` you will always get the same object back.

What if you have a factory that has dependencies? For example, assume you have a class that each time it's called,
you need to inject dependencies into the class. You can use the `resolveFactory` method:

```php
use IceCreamDI\Container;

$container = Container();

$container['service_deps'] = [
  'dep1' => ...,
  ...
];

// Notice how you even have access to the containe object $c.
$container['service'] = $container->factory(function($dep1, $dep2, ..., $c) {
  return new $service($dep1, $dep2, ...);
});

$container->resolveFactory('service', 'service_deps'); // Gives you a new service instance with deps passed in.
```

As you see above we inject the dependencies. We even add on an addition dependency which is the `$c` container object
dependency so that the registered factory that will be resolved has a instance of the container.

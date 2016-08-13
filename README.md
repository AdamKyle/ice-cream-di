# Ice Cream DI

Place Holder.

## Philosophy

Place Holder.

## Examples:

The following is a super basic example of how to use the container.

```php
use IceCreamDI\Container;

$container = Container();

$container['service'] = function() {
  return new Service();
}

$container['services'] // Returns instance of Service class.
```

> ATTN!
>
> We only allow you to store closures in the container that are named.
> attempting to store anything else will result in an invalid argument exception.

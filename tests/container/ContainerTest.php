<?php

use IceCreamDI\Container;

use IceCreamDI\Tests\Fixtures\Service;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase {

    public function testRegisterParamsViaConstructor() {
        $container = new Container(['param' => 'foo']);
        $this->assertEquals('foo', $container['param']);
    }

    public function testContainerIsEmpty() {
        $container = new Container();

        $this->assertEmpty($container->getContainer());
    }

    public function testServiceWasRegistred() {
        $container = new Container();

        $container['service'] = function() {
            return new Service();
        };

        $this->assertNotEmpty($container->getContainer());
        $this->assertInstanceOf(IceCreamDI\Tests\Fixtures\Service::class, $container['service']);
    }

    public function testServiceObjectShouldHaveInstanceOfContainer() {
        $container = new Container();

        $container['service'] = function($c) {
            $service = new Service();

            $service->example = $c;

            return $service;
        };

        $this->assertInstanceOf(IceCreamDI\Container::class, $container['service']->example);
    }

    public function testServiceIsRegisteredInTheContainer() {
        $container = new Container();

        $container['service'] = function() {
            return new Service();
        };

        $this->assertTrue(isset($container['service']));
    }

    public function testServiceIsRemovedFromTheContainer() {
        $container = new Container();

        $container['service'] = function() {
            return new Service();
        };

        unset($container['service']);

        $this->assertFalse(isset($container['service']));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowInvalidArgumentException() {
        $container = new Container();

        $container['service'] = function() {
            return new Service();
        };

        $service = $container['service'];

        $container->extend('service', function($service) {
            $service->example = 'hello';
            return $service;
        });
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testContainerNotRegistered() {
        $container = new Container();
        $container['service'];
    }

    public function testGetFactory() {
        $container = new Container();
        $container['service'] = $container->createFactory(function(){ return new Service(); });

        $service = $container['service'];

        $this->assertInstanceOf(IceCreamDI\Tests\Fixtures\Service::class, $container['service']);
    }

    public function testNotTheSameFactory() {
        $container = new Container();
        $container['service'] = $container->createFactory(function(){ return new Service(); });

        $service    = $container['service'];
        $serviceTwo = $container['service'];

        $this->assertNotSame($service, $serviceTwo);
    }

    public function assertIsSame() {
        $container            = new Container();
        $container['service'] = function() {
            return new Service();
        };

        $service    = $container['service'];
        $serviceTwo = $container['service'];

        $this->assertSame($service, $serviceTwo);
    }

    public function testGetRaw() {
        $container = new Container();

        $container['service'] = $service = $container->createFactory(function() { return 'hey'; });

        $this->assertSame($service, $container->raw('service'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFailToGetRaw() {
        $container = new Container();

        $container->raw('other');
    }

    public function testExtendable() {
        $container = new Container();

        $container['service'] = function() {
            return new Service();
        };

        $container->extend('service', function($service) {
            $service->example = 'hello';

            return $service;
        });

        $this->assertInstanceOf(IceCreamDI\Tests\Fixtures\Service::class, $container['service']);
        $this->assertEquals('hello', $container['service']->example);
    }

    public function testExtendableHasInstanceOfContainer() {
        $container = new Container();

        $container['service'] = function() {
            return new Service();
        };

        $container->extend('service', function($service, $c) {
            $service->example = $c;

            return $service;
        });

        $this->assertInstanceOf(IceCreamDI\Container::class, $container['service']->example);
    }

    public function testResolvingAFactory() {
        $container = new Container();

        $container['service_params'] = [
            'service' => new Service()
        ];

        $container['service'] = $container->createFactory(function(Service $service, $c) {
            $service->example = $c;

            return $service;
        });

        $factoryInstance = $container->resolveFactory('service', 'service_params');

        $this->assertInstanceOf(IceCreamDI\Container::class, $factoryInstance->example);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCantFindNameToResolve() {
        $container = new Container();
        $container->resolveFactory('service', 'service_params');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCantFindParamsToResolve() {
        $container = new Container();

        $container['service'] = 'bob';

        $container->resolveFactory('service', 'service_params');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTryingToResolveNonObjectShouldFail() {
        $container = new Container();

        $container['service'] = 'bob';
        $container['service_params'] = ['bob' => 'bob'];

        $container->resolveFactory('service', 'service_params');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCantFindFactoryToResolve() {
        $container = new Container();

        $container['service'] = function() { return new Service(); };
        $container['service_params'] = ['bob' => 'bob'];

        $container->resolveFactory('service', 'service_params');
    }

    public function testCallAMethodOnAClass() {
        $container = new Container();

        $container['service'] = function() { return new Service(); };

        $response = $container->callMethod('service', 'process');

        $this->assertEquals('hello world', $response);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCallOnNonRegisteredClass() {
        $container = new Container();

        $response = $container->callMethod('xxx', 'process');

        $this->assertEquals('hello world', $response);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCallOnRegisteredClassWithNoMethod() {
        $container = new Container();

        $container['service'] = function() { return new Service(); };

        $response = $container->callMethod('service', 'xxxx');

        $this->assertEquals('hello world', $response);
    }
}

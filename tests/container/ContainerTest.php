<?php

use IceCreamDI\Container;

use IceCreamDI\Tests\Fixtures\Service;

class ContainerTest extends \PHPUnit_Framework_TestCase {

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
        $this->assertFalse($container['service']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testThrowInvalidArgumentException() {
        $container = new Container();

        $container['service'] = 'sam';
    }
}

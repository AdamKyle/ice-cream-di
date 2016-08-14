<?php

namespace IceCreamDI;

class Container implements \ArrayAccess {

    private $_container    = [];
    private $_frozenInTime = [];
    private $_factoryContainer;

    public function __construct(array $values = []) {
        $this->_factoryContainer = new \SplObjectStorage();

        foreach ($values as $valueName => $value) {
            $this->offsetSet($valueName, $value);
        }
    }

    /**
     * Register a callable function to the container.
     *
     * By making the developer pass in a callable function as the named
     * item to be registered, this allows more flexabillity over how the item
     * is handled when returned.
     *
     * It gives the developer more freedom to specify specifically what should happen
     * when registering an item.
     *
     * @param  string $name     - registered item's name. Needs to be unique.
     * @param  callable $func   - registered callable.
     * @return Container $this  - instance of Container
     * @throws InvalidArgumentException
     */
    public function offsetSet($name, $func) {
        if (isset($this->_frozenInTime[$name])) {
            throw new \InvalidArgumentException($name . 'is frozen and cannot be manipulated or overridden.');
        }

        $this->_container[$name] = $func;
    }

    /**
     * Does the item exist in the container?
     *
     * @param  string $name - name of item in the container.
     * @return boolean
     */
    public function offsetExists($name): bool {
        return isset($this->_container[$name]);
    }

    /**
     * Remove an item from the container.
     *
     * @param string $name - name of item to be removed.
     */
    public function offsetUnset($name) {
        unset($this->_frozenInTime[$name]);
        unset($this->_container[$name]);
    }

    /**
     * Get the object from the container based on name.
     *
     * If the same object exists in the factory container we will return that
     * instead.
     *
     * @param  string $name - The name of the object in the container.
     * @return callable or false.
     */
    public function offsetGet($name) {

        if (!isset($this->_container[$name])) {
            throw new \InvalidArgumentException($name . ' is not registered any where in the container.');
        }

        if (!is_object($this->_container[$name])) {
            return $this->_container[$name];
        }

        if (isset($this->_factoryContainer[$this->_container[$name]])) {
            return $this->_container[$name]($this);
        }

        $this->_frozenInTime[$name] = true;

        return $this->_container[$name]($this);
    }

    /**
     * Get the container instance as it currently is.
     *
     * @return array
     */
    public function getContainer(): array {
        return $this->_container;
    }

    /**
     * Get the raw container object.
     *
     * The class definition returned via this call will
     * consititue as the raw return value.
     *
     * This is the non instantiated return value.
     *
     * @param  string $name - name of the container object
     * @return class deffinition
     * @throws InvalidArgumentException
     */
    public function raw(string $name) {
        if (!isset($this->_container[$name])) {
            throw new \InvalidArgumentException($name . ' Does not exist in the container.');
        }

        return $this->_container[$name];
    }

    /**
     * Allows you to create callable factories.
     *
     * @param  callable $callable - closure
     * @return callable
     * @throws InvalidArgumentException
     */
    public function createFactory(callable $callable): callable {
        $this->_factoryContainer->attach($callable);

        return $callable;
    }

    /**
     * Allows you to extend a registered object in the container.
     *
     * @param String $name          - Name of item in container
     * @param Callable $callable    - closure function
     */
    public function extend(string $name, callable $callback) {
        $object = $this->_container[$name];

        $extend = function($c) use ($callback, $object) {
            return $callback($object($c), $c);
        };

        $this[$name] = $extend;
    }

    /**
     * Resolve a factory instance.
     *
     * Resolves a factory instance by passing in specific set of params.
     *
     * Always gives you a new instance.
     *
     * @param string $name       - Name of the object in the container,
     *                             which is compared against that in the
     *                             factory container.
     * @param string $paramsName - Name of the parameters object (an array)
     *                             in the main container.
     * @throws InvalidArgumentException
     *
     */
    public function resolveFactory(string $name, string $paramsName) {

        if(!isset($this->_container[$name])) {
            throw new \InvalidArgumentException($name . ' does not exist in the container.');
        }

        if (!isset($this->_container[$paramsName])) {
            throw new \InvalidArgumentException($paramsName . ' does not exist in the container.');
        }

        if (!is_object($this->_container[$name])) {
            throw new \InvalidArgumentException($paramsName . ' does not return an object.');
        }

        if (!isset($this->_factoryContainer[$this->_container[$name]])) {
            throw new \InvalidArgumentException($name . ' does not exist in the factory container.');
        }

        $this->_container[$paramsName]['containerInstance'] = $this;
        return call_user_func_array($this->_container[$name], $this->_container[$paramsName]);
    }
}

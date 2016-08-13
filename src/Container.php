<?php

namespace IceCreamDI;

class Container implements \ArrayAccess {

    private $_container = [];

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
     * @param string $name     - registered item's name.
     * @param callable $func   - registered callable.
     * @return Container $this - instance of Container
     * @throws InvalidArgumentException
     */
    public function offsetSet($name, $func): Container {
        if (!is_callable($func)) {
            throw new \InvalidArgumentException($func . ' is not callable. You can only register closures.');
        }

        if (!$this->offsetExists($name)) {
            $this->_container[$name] = $func;
        }

        return $this;
    }

    /**
     * Does the item exist in the container?
     *
     * @param string $name - name of item in the container.
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
        unset($this->_container[$name]);
    }

    /**
     * Get the object from the container based on name.
     *
     * @param string $name - The name of the object in the container.
     * @return callable or false.
     */
    public function offsetGet($name) {
        if ($this->offsetExists($name)) {
            return call_user_func($this->_container[$name]);
        }

        return false;
    }

    /**
     * Get the container instance as it currently is.
     *
     * @return array
     */
    public function getContainer(): array {
        return $this->_container;
    }
}

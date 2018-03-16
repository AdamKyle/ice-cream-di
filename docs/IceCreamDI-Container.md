[API Index](ApiIndex.md)


IceCreamDI\Container
---------------


**Class name**: Container

**Namespace**: IceCreamDI



**This class implements**: ArrayAccess



    

    





Properties
----------


**$_container**





    private  $_container = array()






**$_frozenInTime**





    private  $_frozenInTime = array()






**$_factoryContainer**





    private  $_factoryContainer






Methods
-------


public **__construct** (  $values )











**Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| $values | array |  |

--

public **offsetSet** ( string $name, callable $func )


Register a callable function to the container.

By making the developer pass in a callable function as the named
item to be registered, this allows more flexabillity over how the item
is handled when returned.

It gives the developer more freedom to specify specifically what should happen
when registering an item.






**Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | string | &lt;ul&gt;
&lt;li&gt;registered item&#039;s name. Needs to be unique.&lt;/li&gt;
&lt;/ul&gt; |
| $func | callable | &lt;ul&gt;
&lt;li&gt;registered callable.&lt;/li&gt;
&lt;/ul&gt; |

--

public **offsetExists** ( string $name )


Does the item exist in the container?








**Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | string | &lt;ul&gt;
&lt;li&gt;name of item in the container.&lt;/li&gt;
&lt;/ul&gt; |

--

public **offsetUnset** ( string $name )


Remove an item from the container.








**Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | string | &lt;ul&gt;
&lt;li&gt;name of item to be removed.&lt;/li&gt;
&lt;/ul&gt; |

--

public **offsetGet** ( string $name )


Get the object from the container based on name.

If the same object exists in the factory container we will return that
instead.






**Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | string | &lt;ul&gt;
&lt;li&gt;The name of the object in the container.&lt;/li&gt;
&lt;/ul&gt; |

--

public **getContainer** (  )


Get the container instance as it currently is.








--

public **callMethod** ( string $name, string $method )


Call a method on a container instance of the object.

Search the container for a name, assumeing we find one, we can then
call the method name passed in and return the results.






**Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | string |  |
| $method | string |  |

--

public **raw** ( string $name )


Get the raw container object.

The class definition returned via this call will
consititue as the raw return value.

This is the non instantiated return value.






**Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | string | &lt;ul&gt;
&lt;li&gt;name of the container object&lt;/li&gt;
&lt;/ul&gt; |

--

public **createFactory** ( callable $callable )


Allows you to create callable factories.








**Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| $callable | callable | &lt;ul&gt;
&lt;li&gt;closure&lt;/li&gt;
&lt;/ul&gt; |

--

public **extend** ( String $name,  $callback )


Allows you to extend a registered object in the container.








**Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | String | &lt;ul&gt;
&lt;li&gt;Name of item in container&lt;/li&gt;
&lt;/ul&gt; |
| $callback | callable |  |

--

public **resolveFactory** ( string $name, string $paramsName )


Resolve a factory instance.

Resolves a factory instance by passing in specific set of params.

Always gives you a new instance.






**Parameters**:

| Parameter | Type | Description |
|-----------|------|-------------|
| $name | string | &lt;ul&gt;
&lt;li&gt;Name of the object in the container,
which is compared against that in the
factory container.&lt;/li&gt;
&lt;/ul&gt; |
| $paramsName | string | &lt;ul&gt;
&lt;li&gt;Name of the parameters object (an array)
in the main container.&lt;/li&gt;
&lt;/ul&gt; |

--

[API Index](ApiIndex.md)

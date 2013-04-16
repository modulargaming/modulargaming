# Properties

The user module implements the Property interface from [Modular Gaming core](../modulargaming).
The property system provides a easy way to set/get values using keys.

Example of setting points.

~~~
$user->set_property('points', 2000);
~~~

Example of loading points and defaulting to 200 if "null" (never set).

~~~
$points = $user->get_property('points', 2000);
~~~

We can also combine the set and get to increase/decrease the value.

~~~
$user->set_property('points', $user->get_property('points', 2000));
~~~

**It is a good idea to prefix your properties with the module name to avoid conflicts!**
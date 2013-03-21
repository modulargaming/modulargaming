# About MG Forum

Forum is a lightweight forum solution for use with Modular Gaming or Kohana.

Forum requires the Modular Gaming modules [core](../modulargaming), [user](../user) and [admin](../admin) (admin panel) to be enabled You can also run it standalone using the [Kohana forum boilerplate](http://github.com/modulargaming/kohana-forum)

# Standalone

In case you want to use your own user system you can remove the user module by implementing the `Interface_Property` in `Model_User`. You can remove the core class quite easily by copying over the required classes (Controller, Date, Hint, Paginate) and implement your own `Abstract_Controller_Frontend` and `Abstract_View`.

# Modular Gaming

Using the module with Modular Gaming is super easy, simply activate it in bootstrap.php and you are done (ensure core and user modules are enabled).
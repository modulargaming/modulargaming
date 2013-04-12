# User Model

The [user model](../../guide-api/Model_User) represents a user, it implements
[Model_ACL_User](../../guide-api/Model_ACL_User) and [Interface_Property](../../guide-api/Interface_Property).

**TODO: More information, for now just check the api documentation.**

## Avatar
You can easily get the users avatar object ([Avatar](../../guide-api/Avatar) instance) by:
~~~
$user->avatar();
~~~

## Check if user exists
We provide a static function to check if an user exists, that can be used along
with [Validation](../../guide-api/Validation).
~~~
// Directly
Model_User::user_exists($id);

// Or with validation object
$validation->rule('user_id', 'Model_User::user_exists');
~~~

## Creating a user
We have a specific function for creating a user, it defaults the timezone to site standard (from config), and applies
password validation.
~~~
$user = new Model_User;
$user->create_user(
	array( // Data
		'username' => 'Test_User',
		'email'    => 'test@134.com',
		'password' => 'password123',
		'password_confirm' => 'password123'
	),
	array( // Expected values.
		'username',
		'email',
		'password'
	)
);
~~~
Most of the time you want to use post data as the first parameter.

# Permission

## Can

## Assert
Works the same way as can but throws an exception if it fails.
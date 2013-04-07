<?php

use Behat\Behat\Context\Step;
use Behat\Behat\Event\SuiteEvent;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

require_once 'vendor/autoload.php';
require 'kohana.php';

/**
 * Features context.
 */
class FeatureContext extends Behat\MinkExtension\Context\MinkContext
{
	/**
	 * @BeforeSuite
	 */
	public static function prepare(SuiteEvent $event)
	{
		// Clear the user table.
		$query = DB::delete('users')
			->execute();

		// Setup the test user for login.
		$user = new Model_User;
		$user->username = 'username';
		$user->password = 'password';
		$user->email = 'username@test.com';
		$user->timezone_id = 1;
		$user->create();

		$user->add('roles', Model_Role::LOGIN);
	}

	/**
	 * Initializes context.
	 * Every scenario gets it's own context object.
	 *
	 * @param array $parameters context parameters (set them up through behat.yml)
	 */
	public function __construct(array $parameters)
	{

		// Initialize your context here
	}

	/**
	 * @Given /^there is a user with username "([^"]*)" and password "([^"]*)"$/
	 */
	public function thereIsAUserWithUsernameAndPassword($username, $password)
	{
		// Check if the user exists.
		$exists = Auth::instance()->login($username, $password);

		if ( ! $exists)
		{
			throw new Exception('User with username "'.$username.'" and password "'.$password.'" does not exist!');
		}
	}

	/**
	 * @Given /^I am logged in as "([^"]*)"$/
	 */
	public function iAmLoggedInAs($username)
	{
		return array(
			new Step\Given('there is a user with username "' . $username . '" and password "password"'),
			new Step\When('I am on "user/login"'),
			new Step\When('I fill in "username" with "'.$username.'"'),
			new Step\When('I fill in "password" with "password"'),
			new Step\When('I press "Login"')
		);
	}

	/**
	 * @Given /^there is no user with username "([^"]*)"$/
	 */
	public function thereIsNoUserWithUsername($username)
	{
		$user = ORM::factory('user', array('username' => $username));

		if ($user->loaded())
		{
			throw new Exception('The user with username "'.$username.'" exists!');
		}
	}

}

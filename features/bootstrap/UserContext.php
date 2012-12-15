<?php

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Context\Step,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

class UserContext extends BehatContext
{
	
	public function __construct(array $parameters)
	{

	}

	/**
	 * @Given /^there is a user with username "([^"]*)" and password "([^"]*)"$/
	 */
	public function thereIsAUserWithUsernameAndPassword($username, $password)
	{
		$this->thereIsNoUserWithUsername($username);
		
		$user = ORM::Factory('User');
		$user->username = $username;
		$user->password = $password;
		$user->email = 'temp@mail.com';
		$user->create();

		$user->add('roles', ORM::Factory('Role')->where('name', '=', 'login')->find());
	}

	/**
	 * @Given /^there is no user with username "([^"]*)"$/
	 */
	public function thereIsNoUserWithUsername($username)
	{
		DB::Delete('users')
			->where('username', '=', $username)
			->execute();
	}

	/**
	 * @Given /^I login with username "([^"]*)" and password "([^"]*)"$/
	 */
	public function iLoginWithUsernameAndPassword($username, $password) 
	{
		return array(
			new Step\Given('I am on "user/login"'),
			new Step\Given('I should see "Login"'),
			new Step\Given('I should not see a "#kohana_error" element'),
			new Step\Given('I fill in "username" with "' . $username . '"'),
			new Step\Given('I fill in "password" with "' . $password . '"'),
			new Step\Given('I press "Login"')
		);
	}

}
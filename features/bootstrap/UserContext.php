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
	 *	@Then /^there should be a user with username "([^"]*)"$/
	 */
	public function thereShouldBeAUserWithUsername($username)
	{
		$user = ORM::Factory('User')
			->where('username', '=', $username)
			->find();

		if ( ! $user->loaded())
		{
			throw new Exception('There is no user with username "' . $username . '"');
		}
	}

	/**
	 * @Given /^I am logged in as "([^"]*)"$/
	 */
	public function iAmLoggedInAs($username)
	{
		return array(
			new Step\Given('there is a user with username "' . $username . '" and password "password"'),
			new Step\Given('I login with username "' . $username . '" and password "password"')
		);
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

	public function thereShouldBeAUserWithTheEmail($email)
	{
		$user = ORM::Factory('User')
			->where('email', '=', $email)
			->find();

		if ( ! $email->loaded())
		{
			throw new Exception('There is no user with that email "' . $email . '"');
		}
	}


}

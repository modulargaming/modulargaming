Feature: Register
	In order to use guest restricted features
	As a guest without an existing account
	I need to be able to register an account

	Background:
		Given there is no user with username "username"
			And I am on "user/register"
			And I should see "Register"

	Scenario: Register succesfully
		When I fill in the following:
			| username         | username         |
			| email            | email@domain.com |
			| password         | password         |
			| password_confirm | password         |
		And I press "Register"
		Then I should see "Logout"
			And I should not see "Register"
		Then there should be a user with username "username"

	Scenario: Register with too long username
		When I fill in the following:
			| username         | abcdefghijklmnopqrstuvwxyz12345678910         |
			| email            | email@domain.com |
			| password         | password         |
			| password_confirm | password         |
		And I press "Register"
		Then I should see "username must not exceed 32 characters long"

	Scenario: Register with too short password
		When I fill in the following:
			| username         | username         |
			| email            | email@domain.com |
			| password         | passwor         |
			| password_confirm | passwor         |
		And I press "Register"
		Then I should see "password must be at least 8 characters long"

	Scenario: Register with invalid email
		When I fill in the following:
			| username         | username         |
			| email            | email!domain.com |
			| password         | password         |
			| password_confirm | password         |
		And I press "Register"
		Then I should see "email address must be an email address"

	Scenario: Register with confirm password not matching password
		When I fill in the following:
			| username         | username         |
			| email            | email@domain.com |
			| password         | password         |
			| password_confirm | drowssap         |
		And I press "Register"
		Then I should see "password confirm must be the same as password"

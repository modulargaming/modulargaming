Feature: Register
	In order to use guest restricted features
	As a guest without an existing account

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
		Then there should be a user with username "username"

	Scenario: Register with too long username
		Given I register with an invalid username that is more than 24 characters long
		Then I should see "error username too long"

	Scenario: Register with too short username
		Given I register with an invalid username that is less than 8 characters long
		Then I should see "error username too long"

	Scenario: Register with invalid username
		Given I register with an invalid username that is not only "alpha-numeric"
		Then I should see "error username invalid"

	Scenario: Register with too long password
		Given I register with an invalid password that is more than 24 characters long
		Then I should see "error password too long"

	Scenario: Register with too short password
		Given I register with an invalid password that is more less than 8 characters long
		Then I should see "error password too long"

	Scenario: Register with invalid password
		Given I register with an invalid password that is not only "alpha-numeric"
		Then I should see "error password invalid"

	Scenario: Register with too long password
		Given I register with an invalid password that is more than 24 characters long
		Then I should see "error password too long"

	Scenario: Register with invalid email
		Given I register with an invalid email that does not match "email regex"
		Then I should see "error invalid email"

	Scenario: Register with confirm password not matching password
		Given I register with confirm password not having the same input as password.
		Then I should see "error password does not match"


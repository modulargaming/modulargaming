Feature: Edit
	In order to edit user's profile
	As an existing account
	I need to be able to edit the account information

	Background:
		Given I login with username "username" and password "password"
			And I am on "user/edit"
			And I should see "Save"

	Scenario: Edit succesfully
		When I fill in the following:
			| password_current | password            |
			| email            | newemail@domain.com |
			| password         | password            |
			| password_confirm | password            |
		And I press "Save"
		Then I should see "newemail@domain.com"
			And I should not see "email@domain.com"
		Then there should be a user with email "newemail@domain.com"

	Scenario: Edit with too short password
		When I fill in the following:
			| password_current | password         |
			| email            | email@domain.com |
			| password         | passwor         |
			| password_confirm | passwor         |
		And I press "Save"
		Then I should see "password must be at least 8 characters long"

	Scenario: Edit with invalid email
		When I fill in the following:
			| password_current | password         |
			| email            | email!domain.com |
			| password         | password         |
			| password_confirm | password         |
		And I press "Save"
		Then I should see "email address must be an email address"

	Scenario: Edit with confirm password not matching password
		When I fill in the following:
			| password_current | password         |
			| email            | email!domain.com |
			| password         | password         |
			| password_confirm | drowssap         |
		And I press "Save"
		Then I should see "password confirm must be the same as password"

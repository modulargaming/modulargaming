Feature: login

	Background:
		Given there is a user with username "username" and password "password"

	Scenario: Login succesfully
		Given I login with username "username" and password "password"
		Then I should not see "Login"
		And I should see "Logout"

	Scenario: Login with bad password
		Given I login with username "username" and password "WrongPassword"
		Then I should see "error"

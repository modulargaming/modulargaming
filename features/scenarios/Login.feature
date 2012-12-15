Feature: login
	In order to use guest restricted features
	As a guest with existing account
	I need to login with my credential

	Background:
		Given there is a user with username "username" and password "password"

	Scenario: Login succesfully
		Given I login with username "username" and password "password"
		Then I should not see "Login"
			And I should see "Logout"

	Scenario: Login with bad username
		Given there is no user with username "username"
		Given I login with username "username" and password "password"
		Then I should see "error"

	Scenario: Login with bad password
		Given I login with username "username" and password "WrongPassword"
		Then I should see "error"

	Scenario: Cannot view login page if logged in
		Given I login with username "username" and password "password"
		When I go to "user/login"
		Then I should be on "user"
			And I should not see "Login"
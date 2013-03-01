Feature: login
	In order to use guest restricted features
	As a guest with existing account
	I need to be able to login with my credential

	Background:
		When I am on "user/login"
		Then I should see "Login"
			And I should not see a "#kohana_error" element

	Scenario: Login successful
		Given there is a user with username "username" and password "password"
		When I fill in "username" with "username"
			And I fill in "password" with "password"
			And I press "Login"
		Then I should see "You have been logged in!"
			And I should see "Logout"

	Scenario: Cannot view login page if logged in
		Given I am logged in as "username"
		When I go to "user/login"
		Then I should be on "user"

	Scenario: Login with bad username
		Given there is no user with username "nonExistingUser"
		When I fill in "username" with "nonExistingUser"
			And I fill in "password" with "password"
			And I press "Login"
		Then I should see "Login information incorrect!"

	Scenario: Login with bad password
		Given there is a user with username "username" and password "password"
		When I fill in "username" with "username"
			And I fill in "password" with "wrongPassword"
			And I press "Login"
		Then I should see "Login information incorrect!"
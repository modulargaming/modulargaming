Feature: logout
	In order to stop using my account
	As a logged in user
	I need to be able to logout

	Background:
		Given there is a user with username "username" and password "password"
			And I login with username "username" and password "password"
	
	Scenario: Logout succesfully
		Given I should see "Logout"
		When I follow "Logout"
		Then I should see "Login"
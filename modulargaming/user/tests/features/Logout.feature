Feature: logout
	In order to stop using my account
	As a logged in user
	I need to be able to logout

	Background:
		Given I am logged in as "username"
	
	Scenario: Logout from navigation (post)
		Given I should see "Logout"
		When I press "Logout"
		Then I should be on "/"
			And I should see "You are now logged out, see you soon!"
			And I should see "Login"

	Scenario: Logout from user/logout
		When I go to "user/logout"
		Then I should see "Are you sure you want to logout?"
		When I press "Logout"
		Then I should be on "/"
			And I should see "You are now logged out, see you soon!"
			And I should see "Login"
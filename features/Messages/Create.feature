Feature: Create
	In order to use create a message
	As an authenticated user
	I need to be able to create a message

	Background:
		Given I am logged in as "username"
			And I am on "messages/create"
			And I should see "Create Message"

	Scenario: Register succesfully
		When I fill in the following:
			| receiver_id | 1       |
			| subject     | Subject |
			| text        | Text    |
		And I press "Send"
		Then I should see "You have sent a message"

	Scenario: Create with invalid receiver
		When I fill in the following:
			| receiver_id | ASD     |
			| subject     | Subject |
			| text        | Text    |
		And I press "Send"
		Then I should see "models/message/_external.receiver_id.Model_User::user_exists "

	Scenario: Create with valid receiver that do not exist
		When I fill in the following:
			| receiver_id | 0 |
			| subject     | Subject |
			| text        | Text    |
		And I press "Send"
		Then I should see "models/message/_external.receiver_id.Model_User::user_exists "

	Scenario: Create with no subject
		When I fill in the following:
			| receiver_id | 1    |
			| subject     |      |
			| text        | Text |
		And I press "Send"
		Then I should see "subject must not be empty"

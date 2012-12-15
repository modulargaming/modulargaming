Feature: Register
        In order to use the login feature
        I need to be able to register with correct information

        Background:
                Given there is a form with username, email, password and confirm password 

        Scenario: Register succesfully
                Given I register with username "username", email "username@domain.com", password "password" and confirm password "password"
                Then I should not see "Register"
               	And I should be redirected to Welcome

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



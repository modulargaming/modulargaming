# Setting Sytem

Similar to User profiles, user settings allows modules to add pages by using the [Event class](../../guide-api/Event).

**TODO: Explain how to create a new settings page.**
For now check the examples in the user module, it should be quite well documented and self explaining.

To add a new page, call the event with:
~~~
Event::listen('user.settings', function(Model_User $user, Settings $settings) {
	$settings->add_setting(new Setting_Custom($user));
});
~~~

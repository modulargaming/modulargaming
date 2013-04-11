# Avatar

User module provides a flexible Avatar system. It uses a driver system to support multiple "providers", the drivers
included are:

 * Default (Default site avatar)
 * Gallery (User can pick an avatar from his "unlocked" avatars)
 * Gravatar (Gravatar integration, uses the users email)
 * Upload (User can upload his own custom avatar)

The drivers can be enabled/disabled from the config file.

# Writing a driver

Drivers are actually quite simple to create, depending on what you want it to do, in this example we will
rebuild the gravatar driver.

Lets start with creating the class, it should extend Avatar.
~~~
class Avatar_Gravatar extends Avatar {


}
~~~

A driver has 2 public properties, `$id` wich specifies the machine name and `$name` wich is the display name that
is used at user settings. Lets go ahead and add them to our code.

~~~
class Avatar_Gravatar extends Avatar {

	public $id = 'gravatar';
	public $name = 'Gravatar';

}
~~~

Now we are ready to start writing our gravatar specific code, there are 3 functions we must overwrite, `data($data)`, `url()` and `_edit_view()`.

~~~
class Avatar_Gravatar extends Avatar {

	public $id = 'gravatar';
    public $name = 'Gravatar';

    private $url = 'http://www.gravatar.com/avatar/';

	/**
     * Return the save data array.
     *
     * @return array
     */
    public function data($data)
    {
        return array(
            'driver' => 'Gravatar'
        );
    }

    /**
     * Return the url for the avatar.
     *
     * @return string
     */
    public function url()
    {
        return $this->url.md5(strtolower($this->user->email)).'?s=64';
    }

    protected function _edit_view()
    {
        $view = new View_Avatar_Gravatar;
        $view->url = $this->url();
        return $view;
    }

}
~~~

`data` is used for generating the save information, with gravatar we only have to save the Class name, but there
are other use cases, such as the [gallery driver](../../guide-api/Avatar_Gallery#data) where we need to store the selected avatar's id.

`url` is selfexplaining, it generates the avatar url, in this case we hash the user email with md5.

`_edit_view` generates the edit view, we include url for the preview effect.

So lets go ahead and create the edit view and template.
~~~
class View_Avatar_Gravatar extends Abstract_View {

	/**
	 * @var String url to avatar
	 */
	public $url;

}
~~~

As you can see, we only need the bare essential for a view, the template is likewise extremly basic.

~~~
<img src="{{url}}" class="img-polaroid">
<p>
	Use the gravatar linked to your email, you can create or edit it at
	<a href="http://gravatar.com">gravatar.com</a>
</p>
~~~

And we are done, you should now have something that looks like this at user settings, be sure to
enable the driver in the config.
![Gravatar result](gravatar.png)

Be sure to check out the other avatar drivers in the module for more code examples.
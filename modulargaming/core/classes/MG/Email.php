<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Email helper.
 *
 * @package    MG/Core
 * @category   Helpers
 * @author     Modular Gaming Team
 * @copyright  (c) 2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Email extends Kohana_Email {

	/**
	 * Create a new email instance.
	 *
	 * @param   Abstract_View_Email $view
	 * @param   string              $message
	 * @param   string              $type
	 * @return  Email
	 */
	public static function factory($view = NULL, $message = NULL, $type = NULL)
	{
		return new Email($view);
	}

	/**
	 * Setup the standard email information, AND render the templates.
	 *
	 * @param  Abstract_View_Email $view
	 */
	public function __construct(Abstract_View_Email $view)
	{
		// Create a new message, match internal character set
		$this->_message = Swift_Message::newInstance();

		$this->subject($view->subject);

		$from = Kohana::$config->load('email.from');
		$this->from($from['email'], $from['name']);

		$html = Kostache_Email::factory();
		$html->set_type('html');
		$this->message($html->render($view), 'text/html');

		$text = Kostache_Email::factory();
		$text->set_type('text');
		$this->message($text->render($view), 'text/plain');
	}

	/**
	 * Overwrite the to function to support redirect target, for testing.
	 *
	 * @param   string $email
	 * @param   string $name
	 * @param   string $type
	 * @return  Email
	 */
	public function to($email, $name = NULL, $type = 'to')
	{
		$redirect = Kohana::$config->load('email.redirect');

		if ($redirect)
		{
			$email = $redirect;
		}

		return parent::to($email, $name, $type);
	}

}

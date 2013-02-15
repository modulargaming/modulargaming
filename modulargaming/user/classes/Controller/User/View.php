<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * View a users public profile.
 *
 * @package    Modular Gaming
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Controller_User_View extends Abstract_Controller_User {

	/**
	 * View users profile
	 */
	public function action_index()
	{
		$id = $this->request->param('id');

		$user = ORM::factory('User', $id);

		if ( ! $user->loaded())
		{
			throw HTTP_Exception::Factory('404', 'No such user');
		}

		// @TODO, This belongs to the pet module, better to use events?
		$pets = ORM::factory('User_Pet')
			->where('user_id', '=', $user->id)
			->order_by('active', 'desc');

		$paginate = Paginate::factory($pets)
			->execute();
		
		$this->view = new View_User_Profile;
		$this->view->pagination = $paginate->render();
		$this->view->profile_user = $user;
		//$this->view->pets = ORM::factory('User_Pet')->where('user_id', '=', $user->id)->order_by('active', 'desc')->find_all()->as_array();
		$this->view->pets = $paginate->result();
	}

}

<?php defined('SYSPATH') OR die('No direct script access.');

class Abstract_View_Admin extends Abstract_View {

	/**
	 * @var string active root navigation node
	 */
	public $root_node = null;
	/**
	 * @var string|null active sub navigation node
	 */
	public $node = null;

	/**
	 * @var string Current date
	 */
	public $date = null;

	/**
	 * @var string current time
	 */
	public $time = null;

	/**
	 * @var array Contains a cached version of the admin navigation
	 */
	protected $_nav = null;

	/**
	 * Load the nav if needed
	 */
	protected function _load_nav() {
		if($this->_nav == null)
		{
			$nav = array_reverse(Event::fire('admin.nav_list'));
			$list = array(
				array(
					'title' => 'Dashboard',
					'link'  => URL::site('admin'),
					'icon'  => 'icon-home',
				)
			);
			$this->_nav = array_merge($list, $nav);
		}
	}

	/**
	 * Parse the side nav
	 *
	 * @return array|null
	 */
	public function navigation()
	{
		$this->_load_nav();

		$merged = $this->_nav;

		foreach($merged as $id => $content) {
			if($this->root_node == $content['title'])
			{
				$merged[$id]['active'] = TRUE;

				if($this->node != null && array_key_exists('items', $content) && count($content['items']) > 0) {
					foreach($content['items'] as $item => $value)
					{
						$merged[$id]['items'][$item]['active'] = ($value['title'] == $this->node);
					}
				}
			}
			$merged[$id]['subnav'] = (array_key_exists('items', $content) && count($content['items']) > 0);
		}
		return $merged;
	}

	public function assets_head()
	{
		return Assets::factory('head_admin')->render();
	}

	public function assets_body()
	{
		return Assets::factory('body_admin')->render();
	}

	/**
	 * Overwrite the default has_breadcrumb for the admin
	 * @return bool
	 */
	public function has_breadcrumb() {
		return ($this->root_node != 'Dashboard');
	}

	/**
	 * Build the breadcrumb menu
	 * @return array
	 */
	public function breadcrumb() {
		$this->_load_nav();

		$i = 0;

		$list = array(
			array(
				'title' => 'Dashboard',
				'link' => Route::url('admin.index')
			)
		);

		if($this->root_node != 'Dashboard') {
			foreach($this->_nav as $content) {
				if($this->root_node == $content['title'])
				{
					$list[$i]['divider'] = true;
					$i++;

					$list[] = array(
						'title' => $content['title'],
						'link' => $content['link']
					);

					if($this->node != null && array_key_exists('items', $content))
					{
						foreach($content['items'] as $value) {
							if($this->node == $value['title'])
							{
								$list[$i]['divider'] = true;
								$i++;

								$list[] = array(
									'title' => $value['title'],
									'link' => $value['link']
								);
							}
						}
					}
				}
			}
		}
		//set the last added items as active
		$list[$i]['active'] = TRUE;

		return $list;
	}
}

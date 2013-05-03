<?php defined('SYSPATH') OR die('No direct script access.');

class MG_Controller_Admin_Dashboard extends Abstract_Controller_Admin {

	/**
	 * @var string Full url to the news feed.
	 */
	private $_news_feed_url = 'http://www.modulargaming.com/rss.xml';

	public function action_index()
	{
		if ( ! $this->user->can('Admin_Dashboard_Index'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to access admin dashboard index ');
		}

		$feed = $this->_get_news_feed();

		$this->view = new View_Admin_Dashboard_Index;
		$this->view->feed = $feed;
	}

	/**
	 * Retrieve the news feed items. First try from cache, otherwise load it from the website.
	 * @return array
	 */
	private function _get_news_feed()
	{
		$benchmark = Profiler::start('Admin Dashboard', __FUNCTION__);
		$cache = Cache::instance();

		// Attempt to load feed from cache otherwise get it from the website.
		if ( ! $feed = $cache->get('admin.dashboard.news_feed', FALSE))
		{
			try
			{
				$feed = Feed::parse($this->_news_feed_url);
				$cache->set('admin.dashboard.news_feed', $feed, 360);
			}
			catch (Exception $e)
			{
				Hint::error($e);
			}
		}

		Profiler::stop($benchmark);

		return $feed;
	}

}

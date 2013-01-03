<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Forum_Post extends Controller_Frontend {

	protected $protected = TRUE;

	private $post;
	private $topic;
	private $category;

	public function before()
	{
		$id = $this->request->param('id');

		$this->post = ORM::factory('Forum_Post', $id);

		if ( ! $this->post->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum post not found');
		}

		$this->topic = $this->post->topic;
		$this->category = $this->topic->category;

		Breadcrumb::add('Forum', Route::url('forum'));
		Breadcrumb::add($this->category->title, Route::url('forum/category', array('id' => $this->category->id)));
		Breadcrumb::add($this->topic->title, Route::url('forum/topic', array('id' => $this->topic->id)));
	}

	public function action_edit()
	{
		$post = $this->post;

		if ( ! $this->user->can('Forum_Post_Edit', array('post' => $post)))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to edit post');
		}

		Breadcrumb::add('Edit #'.$post->id, Route::url('forum/post', array(
			'action' => 'edit',
			'id'     => $post->id,
		)));

		if ($_POST)
		{
			try {
				$post->values($this->request->post(), array('content'))->save();
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

			Hint::success('Updated post');
			$this->redirect(Route::get('forum/topic')->uri(array('id' => $post->topic->id)));
		}

		$this->view = new View_Forum_Post_Edit;
		$this->view->post = $post;
	}


	public function action_delete()
	{
		$post = $this->post;

		if ( ! $this->user->can('Forum_Post_Delete', array('post' => $post)))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to delete post');
		}

		Breadcrumb::add('Delete #'.$post->id, Route::url('forum/post', array(
			'action' => 'delete',
			'id'     => $post->id,
		)));

		if ($_POST)
		{
			$topic_redirect = Route::get('forum/topic')->uri(array('id' => $post->topic->id));
			try {

				// First post? delete the topic.
				if ($post->id == $post->topic->posts->limit(1)->find()->id)
				{
					$topic_redirect = Route::get('forum/category')->uri(array('id' => $post->topic->category));

					$post->topic->delete_posts();
					$post->topic->delete();
				}

				$user = $post->user;
				$post->delete();
				$user->calculate_post_count();
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

			Hint::success('Deleted post');
			$this->redirect($topic_redirect);
		}

		$this->view = new View_Forum_Post_Delete;
		$this->view->post = $post;
	}

}
<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Forum_Post extends Controller_Frontend {

	protected $protected = TRUE;

	public function action_edit()
	{
		$id = $this->request->param('id');

		$post = ORM::factory('Forum_Post', $id);

		if ( ! $post->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum post not found');
		}

		if ( ! $this->user->can('Forum_Post_Edit', array('post' => $post)))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to edit post');
		}

		$topic = $post->topic;
		
		Breadcrumb::add('Forum', Route::url('forum'));
		Breadcrumb::add($topic->category->title, Route::url('forum/category', array('id' => $topic->category->id)));
		Breadcrumb::add($topic->title, Route::url('forum/topic', array('id' => $topic->id)));
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
		$id = $this->request->param('id');

		$post = ORM::factory('Forum_Post', $id);

		if ( ! $post->loaded())
		{
			throw HTTP_Exception::factory('404', 'Forum post not found');
		}

		if ( ! $this->user->can('Forum_Post_Delete', array('post' => $post)))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to delete post');
		}

		$topic = $post->topic;
		
		Breadcrumb::add('Forum', Route::url('forum'));
		Breadcrumb::add($topic->category->title, Route::url('forum/category', array('id' => $topic->category->id)));
		Breadcrumb::add($topic->title, Route::url('forum/topic', array('id' => $topic->id)));
		Breadcrumb::add('Delete #'.$post->id, Route::url('forum/post', array(
			'action' => 'delete',
			'id'     => $post->id,
		)));

		if ($_POST)
		{
			try {
				$post_redirect = $post->topic->id;
				$post->delete();
			}
			catch (ORM_Validation_Exception $e)
			{
				Hint::error($e->errors('models'));
			}

			Hint::success('Deleted post');
			$this->redirect(Route::get('forum/topic')->uri(array('id' => $post_redirect)));
		}

		$this->view = new View_Forum_Post_Delete;
		$this->view->post = $post;
	}


}


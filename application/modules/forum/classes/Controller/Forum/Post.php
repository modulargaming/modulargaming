<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Forum_Post extends Controller_Frontend {

        public function action_view()
        {
                $id = $this->request->param('id');

                $post = ORM::factory('Forum_Post', $id);

                if ( ! $post->loaded())
                {
                        throw HTTP_Exception::factory('404', 'Forum post not found');
                }

                $this->view = new View_Forum_Post;
                $this->view->post = $post->as_array();
        }

}


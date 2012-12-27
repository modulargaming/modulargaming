<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Topic extends View_Base {

        public $topic;
        public $posts;

        public function title()
        {
                return 'Forum - ' . $this->topic->title;
        }

        public function topic()
        {
                return $this->topic->as_array();
        }

        public function posts()
        {
                $posts = array();

                foreach ($this->posts as $post)
                {
                        $posts[] = array(
                                'title' => $post->title,
                                'href' => Route::url('forum/post', array('id' => $post->id)),
                        );
                }

                return $posts;
        }

}


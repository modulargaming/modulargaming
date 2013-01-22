<?php defined('SYSPATH') or die('No direct script access.');
 
class Task_Pet extends Minion_Task
{
    protected $_options = array(
        'limit' => 4,
        'color' => NULL,
    );
 
    /**
     * This is a task to decrease pet happiness and hunger
     *
     * @return null
     */
    protected function _execute(array $params)
    {
$query = DB::update('user_pets')->set(array('hunger' => DB::expr('`hunger` - 1')))->execute();
$query = DB::update('user_pets')->set(array('happiness' => DB::expr('`happiness` - 1')))->execute();
    }
}

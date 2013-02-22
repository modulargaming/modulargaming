<?php defined('SYSPATH') or die('No direct script access.');

class Task_Pet extends Minion_Task
{
    protected $_options = array(
        'limit' => 4,
        'amount' => 1,
    );

    /**
     * This is a task to decrease pet happiness and hunger
     *
     * @return null
     */
    protected function _execute(array $params)
    {
$query = DB::update('user_pets')->set(array('hunger' => DB::expr("hunger - $params[amount]")))->execute();
$query = DB::update('user_pets')->set(array('happiness' => DB::expr("happiness - $params[amount]")))->execute();
    }
}

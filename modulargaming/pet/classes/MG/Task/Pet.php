<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Task to decrease pet hunger and happiness
 *
 * @package    MG/Pet
 * @category   Task
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Task_Pet extends Minion_Task
{
    protected $_options = array(
        'limit' => 4,
        'amount' => 1,
    );

    /**
     * This is a task to decrease pet happiness AND hunger
     *
     * @return NULL
     */
    protected function _execute(array $params)
    {
$query = DB::update('user_pets')->set(array('hunger' => DB::expr("hunger - $params[amount]")))->execute();
$query = DB::update('user_pets')->set(array('happiness' => DB::expr("happiness - $params[amount]")))->execute();
    }
}

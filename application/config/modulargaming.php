<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
'encrypt_key' => 'change',
'cookie_salt' => 'change',
'session_lifetime' => 0,
'header' => array
(
'title' => 'Modular Gaming',
'keyword' => 'Modular Gaming',
),
'language' => array
(
'en',
),
'account'	=> array
(
'create'	=> array
(
'username'	=> array
(
'min_length'	=> 2,
'max_length'	=> 12,
'format'	=> 'alpha_numeric', // alpha_dash, alpha
),
'password'	=> array
(
'min_length'	=> 6,
)
),
),
);

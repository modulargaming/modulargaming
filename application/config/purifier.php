<?php defined('SYSPATH') or die('No direct script access.');

return array(
        'finalize' => TRUE,
        'preload'  => FALSE,
);


        'settings' => array(
                /**
                 * Use the application cache for HTML Purifier
                 */
        'HTML.Allowed' =>       'h1,h2,i,b,u,a[href],img[src],
                                blockquote[style],ul,ol,li,br',
        'Cache.SerializerPath' => APPPATH.'cache',
        ),


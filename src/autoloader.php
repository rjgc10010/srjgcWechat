<?php
spl_autoload_register(function ($class) {
    if ( 0 === strpos( $class, 'SRjgcWechat' ) ) { // Autoload our packages only

        $base_dir = __DIR__ . '/';
        $file = str_replace('\\', '/', $base_dir . $class . '.php'); // Change \ to /
       // print_r($file);die;
        require_once $file;
    }
});
<?php
if (!isset($_SESSION)) {
    session_start();
}

function t($key) {
    static $lang_strings = null;
    
    if ($lang_strings === null) {
        $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
        $lang_file = __DIR__ . '/../lang/' . $lang . '.php';
        
        if (file_exists($lang_file)) {
            $lang_strings = require $lang_file;
        } else {
            $lang_strings = require __DIR__ . '/../lang/en.php';
        }
    }
    
    return isset($lang_strings[$key]) ? $lang_strings[$key] : $key;
}
?>

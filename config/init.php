<?php

session_start();

// Time
date_default_timezone_set("Europe/Dublin");

// Web Path
define("PORT", isset($_SERVER['HTTPS']) ? "https" : "http");
define("PATH", dirname(dirname(__FILE__)));
define("FOLDER", basename(PATH));

if (strpos($_SERVER['PHP_SELF'], FOLDER) !== false) {
    define("ABSPATH", PORT . "://" . $_SERVER['HTTP_HOST'] . "/" . FOLDER . "/");
} else {
    define("ABSPATH", PORT . "://" . $_SERVER['HTTP_HOST'] . "/");
}

define("CONFIG", PATH . "/config");
define("UPLOAD", PATH . "/content/uploads");

define("IMAGES", ABSPATH . "content/images");
define("VIDEOS", ABSPATH . "content/videos");
define("LIBRARY", ABSPATH . "library");

// Autoload php Class

spl_autoload_extensions(".php");

function load_modules($name) {

    $dir_modules = PATH . "/app";
    $modules = scandir($dir_modules);
    foreach ($modules as $module) {
        if ($module != "." && $module != "..") {
            $name = str_replace("Controller", ".controller", $name);
            $name = str_replace("Model", ".model", $name);

            $name = strtolower($name);
            $part = explode("\\", $name);
            $name = end($part);

            # Models
            $file_path = "{$dir_modules}/{$module}/models/{$name}.php";
            if (file_exists($file_path)) {
                include_once $file_path;
            }

            # Controllers
            $file_path = "{$dir_modules}/{$module}/controllers/{$name}.php";
            if (file_exists($file_path)) {
                include_once $file_path;
            }
        }
    }
}

function load_core($name) {
    $name = strtolower($name);
    $part = explode("\\", $name);
    $name = end($part);

    $dir_core = PATH . "/core";
    $file_path = $dir_core . "/{$name}.class.php";
    if (file_exists($file_path)) {
        include_once $file_path;
    }

    $sections = scandir($dir_core);
    foreach ($sections as $section) {
        if ($section != "." && $section != "..") {
            $file_path = "{$dir_core}/{$section}/$name.class.php";
            if (file_exists($file_path)) {
                include_once $file_path;
            }
        }
    }
}

spl_autoload_register('load_core');
spl_autoload_register('load_modules');

// Set Environment values
include_once 'environment.php';

// Set Environment values
require_once PATH . '/vendor/autoload.php';


<?php

define('APP_ROOT', __DIR__);

/*
 * Make classes within the application visible
 */
set_include_path(get_include_path().PATH_SEPARATOR.__DIR__);

/*
 * Register default autoloader. 
 * 
 * Ensures that classes are included when code attempts to instantiate them.
 */
spl_autoload_register();

/*
 * Set logging preferences for the application
 * 
 * This would usually be different based on environment and controlled through
 * some form of config. 
 */
ini_set('display_errors', 'Off'); 
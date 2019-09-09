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

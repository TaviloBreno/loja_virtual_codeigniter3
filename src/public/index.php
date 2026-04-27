<?php

declare(strict_types=1);

define('ENVIRONMENT', 'development');

switch (ENVIRONMENT) {
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', '1');
        break;
    case 'testing':
    case 'production':
        ini_set('display_errors', '0');
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', true, 503);
        echo 'The application environment is not set correctly.';
        exit(1);
}

$system_path = dirname(__FILE__) . '/../system';
$application_folder = dirname(__FILE__) . '/../application';
$view_folder = dirname(__FILE__) . '/../application/views';

// Define os caminhos
define('BASEPATH', rtrim($system_path, '/') . '/');
define('APPPATH', rtrim($application_folder, '/') . '/');
define('VIEWPATH', rtrim($view_folder, '/') . '/');

// Carrega o CodeIgniter
require_once BASEPATH . 'core/CodeIgniter.php';

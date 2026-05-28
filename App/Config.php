<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */

class Config
{
    const DB_HOST = DB_HOST_VALUE;
    const DB_NAME = DB_NAME_VALUE;
    const DB_USER = DB_USER_VALUE;
    const DB_PASSWORD = DB_PASSWORD_VALUE;
    const SHOW_ERRORS = true;
}

define('DB_HOST_VALUE', $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost');
define('DB_NAME_VALUE', $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'videgrenierenligne');
define('DB_USER_VALUE', $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'webapplication');
define('DB_PASSWORD_VALUE', $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? '653rag9T');
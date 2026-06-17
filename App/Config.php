<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */

define('DB_HOST_VALUE', $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost');
define('DB_NAME_VALUE', $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'videgrenierenligne');
define('DB_USER_VALUE', $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'webapplication');
define('DB_PASSWORD_VALUE', $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? '653rag9T');

define('MAILTRAP_HOST_VALUE', $_ENV['MAILTRAP_HOST'] ?? getenv('MAILTRAP_HOST') ?? 'sandbox.smtp.mailtrap.io');
define('MAILTRAP_PORT_VALUE', $_ENV['MAILTRAP_PORT'] ?? getenv('MAILTRAP_PORT') ?? '2525');
define('MAILTRAP_USER_VALUE', $_ENV['MAILTRAP_USER'] ?? getenv('MAILTRAP_USER') ?? '');
define('MAILTRAP_PASS_VALUE', $_ENV['MAILTRAP_PASS'] ?? getenv('MAILTRAP_PASS') ?? '');
define('MAILTRAP_FROM_VALUE', $_ENV['MAILTRAP_FROM'] ?? getenv('MAILTRAP_FROM') ?? 'noreply@videgrenierenligne.fr');

define('APP_ENV', $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?? 'dev');

class Config
{
    const DB_HOST = DB_HOST_VALUE;
    const DB_NAME = DB_NAME_VALUE;
    const DB_USER = DB_USER_VALUE;
    const DB_PASSWORD = DB_PASSWORD_VALUE;
    const SHOW_ERRORS = APP_ENV;

    const MAILTRAP_HOST = MAILTRAP_HOST_VALUE;
    const MAILTRAP_PORT = MAILTRAP_PORT_VALUE;
    const MAILTRAP_USER = MAILTRAP_USER_VALUE;
    const MAILTRAP_PASS = MAILTRAP_PASS_VALUE;
    const MAILTRAP_FROM = MAILTRAP_FROM_VALUE;
}
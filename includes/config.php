<?php

/**
 * @Note : The url of the all css, js and the logo files used form the home of the site.
 * This file configure the site, and it's related data.
 */

/** These all are the site details. */
if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'WebzCloths');
}

if (!defined('SITE_SLUG')) {
    define('SITE_SLUG', 'buy the world here');
}

if (!defined('ADMIN_NUMBER')) {
    define('ADMIN_NUMBER', '2514369852');
}

if (!defined('ADMIN_EMAIL')) {
    define('ADMIN_EMAIL', 'amazecart991@gmail.com');
}

if (!defined('SITE_OWNER')) {
    define('SITE_OWNER', 'WebDev Community');
}

if (!defined('SITE_DESIGNER')) {
    define('SITE_DESIGNER', 'WebDev Community');
}

if (!defined('SITE_PUBLISHED_YEAR')) {
    define('SITE_PUBLISHED_YEAR', '2024');
}

if (!defined('LOGO_URL')) {
    define('LOGO_URL', '/assets/images/logo.png');
}

if (!defined('SMTP_EMAIL')) {
    define('SMTP_EMAIL', 'bhandarisameer512@gmail.com');
}

if (!defined('SMTP_PASSWORD')) {
    define('SMTP_PASSWORD', 'shuqarukimqsjucq');
}

if (!defined('DBHOST')) {
    define('DBHOST', 'localhost');
}

if (!defined('DBUSER')) {
    define('DBUSER', 'root');
}

if (!defined('DBPASS')) {
    define('DBPASS', '');
}
if (!defined('DBNAME')) {
    define('DBNAME', 'webzcloths');
}

/** This class manage all default files for the admin panel. */
class files_default
{    
    /**  This is the sidebar Array. */
    protected array $ArrHeader = array(
        'Home' => [
            'link' => '/index.php'
        ],
        'Cart' => [
            'link' => '/cart.php'
        ],
        'Edit User' => [
            'link' => '/edit.php',
            'user_type' => [1]
        ],
        'Edit Products' => [
            'link' => '/edit.php',
            'user_type' => [0,1]
        ],
        'About Us' => [
            'link' => '/about.php'
        ],
        'Contact Us' => [
            'link' => '/contact.php'
        ]
    );

    /** This css files will automatically include all the pages. */
    protected array $css = array(
        '/assets/css/style.css'
    );

    protected array $js = array(
        '/assets/js/jquery.min.js',
        '/assets/js/validation.min.js',
        '/assets/js/app.js'
    );
}
<?php
/** Loading all required files. */
require_once __DIR__ . '/includes/load.php';

/** Checking the required filters. */
$filter = new filter();
$filter->unset_verify_user();

if (!$filter->check_authentication())
{
    /** Details configuration for the page. */
    $details = array(
        'title' => 'Register'
    );

    /** Required files for the page. */
    $files = array(
        'css' => [
            '/assets/css/auth.css'
        ],
        'js' => [
            '/assets/js/authentication.js'
        ]
    );

    $site_name = SITE_NAME;

    /** Body of the page. */
    $body = <<<EOPAGE
    <div class="container">
        <div class="logo">
            <div class="text">
                {$site_name}
            </div>
        </div>
        <div class="main">
            <div class="header">
                <h1>Register</h1>
            </div>
            <div id="error_msg"></div>
            <form id="register" method="post">
                <div class="content">
                    <input type="text" name="name" placeholder="Name" class="form-input name" autocomplete="off">
                    <input type="email" name="email" placeholder="Email" class="form-input email" autocomplete="off">
                    <input type="password" placeholder="Password" name="password" class="form-input password" autocomplete="off">
                </div>
                <div class="login">
                    <button type="submit" class="form-submit" name="sign_up" id="sign_up">Register</button>
                </div>
                <div class="or">
                    <span>OR</span>
                </div>
                <div class="login">
                    <button type="button" onclick="window.location.href = '/login'" class="form-submit next">Login</button>
                </div>
            </form>
        </div>
    </div>
EOPAGE;

    /** Creating the page. */
    new page($details, $files, $body, true);
} else {
    /** Goto home page */
    goto_home();
}
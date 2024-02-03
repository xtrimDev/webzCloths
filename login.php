<?php
/** Loading all required files. */
require_once __DIR__ . '/includes/load.php';

/** Checking the required filters. */
$filter = new filter();
$filter->unset_verify_user();

if (!$filter->check_authentication())
{
    $url = new url();
    $auth = new Authentication();
    $smtp = new simple_male_transfter_protocol();
    $db = new Database();

    /** Details configuration for the page. */
    $details = array(
        'title' => 'Login'
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
                <h1>Login</h1>
            </div>
            <div id="error_msg"></div>
            <form method="post" id="login">
                <div class="content">
                    <input type="email" name="email" placeholder="Email" class="form-input username" autocomplete="off">
                    <input type="password" placeholder="Password" name="password" class="form-input password" autocomplete="off">
                </div>
                <div class="login">
                    <button type="submit" class="form-submit" name="sign_in" id="sign_in">Login</button>
                </div>
                <div class="or">
                    <span>OR</span>
                </div>
                <div class="login">
                    <button type="button" onclick="window.location.href = '/register'" class="form-submit next">Register</button>
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
<?php
session_start();

/** Loading all required files. */
require_once __DIR__ . '/includes/load.php';

/** Checking the required filters. */
$filter = new filter();

if (!$filter->check_authentication())
{
    $auth = new Authentication();
    $url = new url();
    $smtp = new simple_male_transfter_protocol();
    $db = new Database();

    if (isset($_GET['verify']) && $filter->check_verify_user_status()) {
        $msg = '';
        if (isset($_GET['resend']) && $_GET['resend'] == 'verify')
        {
            $run = $db->query("SELECT * FROM `user_temp` WHERE `user_id` = ?", array($_SESSION[SITE_NAME . '_TEMP_VERIFY_ID']));

            $user_data = $run->fetchArray();
            $smtp->to = $db->query("SELECT * FROM `user` WHERE `id` = ?", array($_SESSION[SITE_NAME . '_TEMP_VERIFY_ID']))->fetchArray()['email'];

            $smtp->subject = 'OTP for password reset';
            $smtp->message = "your OTP for password reset is " . $user_data['temp_code'];

            if ($smtp->sent()) {
                $msg = true;
                $_SESSION['otp_sent'] = true;
                header('location: '.$url->home().'/auth.php?verify');
                die();
            } else {
                $msg = false;
            }
        }

        /** Details configuration for the page. */
        $details = array(
            'title' => 'Verification'
        );

        /** Required files for the page. */
        $files = array(
            'css' => [
                '/assets/css/otp.css'
            ],
            'js' => [
                '/assets/js/jquery.min.js',
                '/assets/js/validation.min.js',
                '/assets/js/otp.js'
            ]
        );

        $run = $db->query("SELECT * FROM `user` WHERE `id` = ?", array($_SESSION[SITE_NAME . '_TEMP_VERIFY_ID']));

        $user_data = $run->fetchArray();
        $user_email = partiallyhideEmailAddress($user_data['email']);

        if (!empty($msg) && $msg != '' && $msg || isset($_SESSION['otp_sent']) && $_SESSION['otp_sent']) {
            (isset($_SESSION['otp_sent']) ? $_SESSION['otp_sent'] = false : '');
            $error = <<<EOPAGE

        <div class="msg_danger" style="background: #1c7430; color: white;"> <i class="fa-solid fa-circle-info"></i> &nbsp; Code Sent Successfully!</div>
    
EOPAGE;
        } else if (!empty($msg) && $msg != '' && !$msg) {
            $error = <<<EOPAGE

        <div class="msg_danger"> <i class="fa-solid fa-circle-info"></i> &nbsp; Something Went Wrong!</div>
    
EOPAGE;
        } else {
            $error = '';
        }

        /** Body of the page. */
        $body = <<<EOPAGE
<form id="verify_otp" onsubmit="return false" class="container_otp">
    <h1>OTP Verification</h1>
    <p>Code has been sent to {$user_email}</p>
    <div id="error_msg" style="margin-top: 30px;">{$error}</div>
    <div class="code-container">
        <input type="number" name="int1" id="int1" class="code" min="0" max="9">
        <input type="number" name="int2" id="int2" class="code" min="0" max="9">
        <input type="number" name="int3" id="int3" class="code" min="0" max="9">
        <input type="number" name="int4" id="int4" class="code" min="0" max="9">
        <input type="number" name="int5" id="int5" class="code" min="0" max="9">
        <input type="number" name="int6" id="int6" class="code" min="0" max="9">
    </div>
    <div>
        <button type="submit" id="otp_verify" name="otp_verify" class="btn btn-primary">Verify</button>
    </div>
    <small>
        Didn't receive the Code? <strong><a href="{$url->current()}&resend=verify">Resend</a></strong>
    </small>
</form>
EOPAGE;
        /** Creating the page. */
        new page($details, $files, $body, true);
        die();
    } else {
        $filter->unset_verify_user();
        echo "Access Forbidden";
    }
} else {
    echo "Another session is already active";
}
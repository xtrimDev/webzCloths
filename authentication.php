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

    if (isset($_POST['sign_in']) && isset($_POST['email']) && isset($_POST['password'])) {
        if ($url->previous() && strpos($url->previous(), $url->home()) !== false)
        {
            if ($auth->login(trim($_POST['email']), trim($_POST['password'])))
            {
                if ($filter->set_authentication($auth->output['email'], $auth->output['filter']))
                {
                    echo "success";
                } else {
                    echo "Something Went wrong!";
                }
            } else {
                /**
                 * Error types
                 *
                 * 101 Not found
                 * 102 Need verification
                 * 103 Banned
                 */
                if ($auth->error == 101)
                {
                    echo "Invalid Email or password!";
                } else if ($auth->error == 102) {
                    //echo "Your verification is not complete!";
                    echo "102";
                } else if ($auth->error == 103) {
                    echo "Your account is banned or disabled!";
                } else {
                    echo "Something Went wrong!";
                }
            }
        } else {
            echo "Access Forbidden";
        }
    } else if (isset($_POST['sign_up']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
        if ($url->previous() && strpos($url->previous(), $url->home()) !== false)
        {
            if ($auth->register(trim($_POST['name']), trim($_POST['email']), trim($_POST['password'])))
            {
                if ($filter->set_verify_user($auth->output['id'], $auth->output['temp_verify']))
                {
                    $smtp->to = trim($_POST['email']);
                    $smtp->subject = 'User verification';
                    $smtp->message = "your One Time Password for " . SITE_NAME . " is " . $auth->output['temp_code'];

                    if ($smtp->sent())
                    {
                        $_SESSION['otp_sent'] = true;
                        echo "success";
                    } else {
                        $filter->unset_verify_user();
                        $auth->remove($auth->output['id']);
                        echo "Something Went wrong!";
                    }
                } else {
                    $auth->remove($auth->output['id']);
                    echo "Something Went wrong!";
                }
            } else {
                /**
                 * Error types
                 *
                 * 104 Already exist
                 */
                if ($auth->error == 104)
                {
                    echo "This email already registered!";
                } else {
                    echo "Something went wrong!";
                }
            }
        } else {
            echo "Access Forbidden";
        }
    } elseif (isset($_POST['otp_verify']) && isset($_POST['int1']) && isset($_POST['int2']) && isset($_POST['int3']) && isset($_POST['int4']) && isset($_POST['int5']) && isset($_POST['int6'])) {
        $otp = trim($_POST['int1']) . trim($_POST['int2']) . trim($_POST['int3']) . trim($_POST['int4']) . trim($_POST['int5']) . trim($_POST['int6']);

        if ($filter->check_verify_user_status())
        {
            if ($auth->verify_otp($otp, $_SESSION[SITE_NAME . '_TEMP_VERIFY_ID']))
            {
                if ($filter->set_authentication($auth->output['email'], $auth->output['filter']))
                {
                    echo "success";
                } else {
                    $filter->unset_verify_user();
                    echo "Something Went wrong!";
                }
            } else {
                echo "Enter a valid code!";
            }
        } else {
            echo "Session is over.";
        }
    } else {
        echo "Access Forbidden";
    }
} else {
    echo "Another session is already active";
}

<?php
/** Loading all required files. */
require_once __DIR__ . '/includes/load.php';

$filter = new filter();
$url = new url();
$auth = new Authentication();
$db = new Database();

if ($filter->check_authentication()) {
    if (isset($_GET['cart']) && !empty($_POST['product_id'])) {
        $auth->getuserdata(NULL, $_COOKIE[SITE_NAME . '_AUTH_FILTER_FOR']);
        $user_type = $auth->output['id'];

        $query = $db->query("SELECT * FROM `cart` WHERE `p_id` = ? AND `u_id` = ?", array($_POST['product_id'], $user_type));

        if (!$query->numRows()) {
            $query = $db->query("INSERT INTO `cart`(`p_id`, `u_id`) VALUES (?,?)", array($_POST['product_id'], $user_type));
            if ($query) {
                echo "success";
            } else {
                echo "Something went wrong";
            }
        } else {
            echo "Already done";
        }
    } elseif (isset($_GET['cartRemove']) && !empty($_POST['product_id'])) {
        $auth->getuserdata(NULL, $_COOKIE[SITE_NAME . '_AUTH_FILTER_FOR']);
        $user_type = $auth->output['id'];

        $query = $db->query("SELECT * FROM `cart` WHERE `p_id` = ? AND `u_id` = ?", array($_POST['product_id'], $user_type));

        if ($query->numRows()) {
            $query = $db->query("DELETE FROM `cart` WHERE`p_id` = ? AND `u_id` = ?", array($_POST['product_id'], $user_type));
            if ($query) {
                echo "success";
            } else {
                echo "Something went wrong";
            }
        } else {
            echo "Already done";
        }
    } else {
        echo "Access Denied";
    }
} else {
    echo "Auth Error";
}

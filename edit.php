<?php
/** Loading all required files. */
require_once __DIR__ . '/includes/load.php';

$filter = new filter();
$url = new url();
$auth = new Authentication();
$db = new Database();


if ($filter->check_authentication() && $auth->getuserdata(NULL, $_COOKIE[SITE_NAME . '_AUTH_FILTER_FOR']) && $auth->output['user_type'] === '1') {
    $user_id = $auth->output['id'];
    $files = [
        'css' => '/assets/css/edit.css',
        'js' => '/assets/js/edit.js'
    ];

    $details = ['title'=> 'Edit Users'];

    if (isset($_GET['remove']) && !empty($_POST['u_id'])) {
        if ($db->query("DELETE FROM `user` WHERE `id` = ?", $_POST['u_id'])){
            echo "success";
        } else {
            echo "Something Went Wrong!";
        }
        die();
    } else if (isset($_GET['disable']) && !empty($_POST['u_id'])) {
        $query = $db->query("SELECT * FROM `user` WHERE `id` = ? AND `status` = '0'", $_POST['u_id']);

        if ($query) {
            if ($query->numRows()) {
                $query = $db->query("UPDATE `user` SET `status`='1' WHERE `id` = ?", $_POST['u_id']);
                echo "Enabled";
            } else {
                $query = $db->query("UPDATE `user` SET `status`='0' WHERE `id` = ?", $_POST['u_id']);
                echo "Disabled";
            }
        } else {
            echo "Something Went Wrong";
        }
        die();
    } else {
        $body = '';

        $query = $db->query("SELECT * FROM `user` WHERE `id` <> '1' AND `id` <> ? ORDER BY `id` ASC ", $user_id);

        if ($query->numRows()) {
            $body .= <<<EOPAGE
        <nav class="admnav2">
            <div class="userlist" style="font-size: 2.5rem; width: 100%; text-align: center; cursor:auto;">User List
                
            </div>
        </nav>
        <nav class="admnav3">
            <div class="entrynumber">
            </div>
        </nav>
        <div class="table">
            <table>
                <thead>
                   <tr class="data">
                       <th class="th1">ID</th>
                       <th class="th2">Name</th>
                       <th class="th3">Email</th>
                       <th class="th3">Password</th>
                       <th class="th4">Profile_img</th>
                       <th class="th5">Registered On</th>
                       <th class="th6">Status</th>
                       <th class="th7">User Type</th>
                       <th class="th8">Action</th>
                   </tr>
               </thead>
EOPAGE;

            foreach ($query->fetchAll() as $rand => $user) {
                if ($user['status'] === '-1') {
                    $status = "Need Verification";
                } elseif ($user['status'] === '0') {
                    $status = 'Banned.';
                } elseif ($user['status'] === '1') {
                    $status = "Active";
                } else {
                    $status = "something went wrong!";
                }

                if ($user['user_type'] === '-1') {
                    $user_type = "Customer";
                } elseif ($user['user_type'] === '0') {
                    $user_type = 'Worker';
                } elseif ($user['user_type'] === '1') {
                    $user_type = "Admin";
                } else {
                    $user_type = 'Unknown';
                }

                if ($user['status'] == '0') {
                    $enable = <<<EOPAGE
                        <a href="javascript:void(0)" data-user-id="{$user['id']}" class="disable">Enable</a>
EOPAGE;
                } else if ($user['status'] == '1') {
                    $enable = <<<EOPAGE
                        <a href="javascript:void(0)" data-user-id="{$user['id']}" class="disable">Disable</a>
EOPAGE;
                } else {
                    $enable = '';
                }

                $body .= <<<EOPAGE
                <thead>
                   <tr>
                       <td class="td1">{$user['id']}</td>
                       <td class="td2">{$user['name']}</td>
                       <td class="td3">{$user['email']}</td>
                       <td class="td3">{$user['password']}</td>
                       <td class="td4">{$user['profile_img']}</td>
                       <td class="td5">{$user['registered_on']}</td>
                       <td class="td6">{$status}</td>
                       <td class="td7">{$user_type}</td>
                       <td>
                           <div class="actiondiv">
                               <a href="javascript:void(0)" data-user-id="{$user['id']}" class="remove">Remove</a>
                               {$enable}
                           </div>
                       </td>
                   </tr>
               </thead>
EOPAGE;
            }

            $body .= <<<EOPAGE
            </table>
        </div>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
EOPAGE;
        }
    }
} else {
    header('HTTP/1.0 404 Not Found');
    $files = [];
    $body = <<<EOPAGE
        <style>@import "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css";</style>
        <div class="d-flex align-items-center justify-content-center" style="height: 50vh">
            <div class="text-center">
                <h1 class="display-1 fw-bold">404</h1>
                <p class="fs-3"> <span class="text-danger">Opps!</span> Page not found.</p>
                <p class="lead">
                    The page you’re looking for doesn’t exist.
                  </p>
                <a href="{$url->home()}" class="btn btn-primary">Go Home</a>
            </div>
        </div>
EOPAGE;
    $details = ['title' => '404 page not found'];
}

new page($details, $files, $body, false);

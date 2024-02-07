<?php
/** Loading all required files. */
require_once __DIR__ . '/includes/load.php';

$filter = new filter();
$url = new url();
$auth = new Authentication();
$db = new Database();

if ($filter->check_authentication() && $auth->getuserdata(NULL, $_COOKIE[SITE_NAME . '_AUTH_FILTER_FOR']) && ($auth->output['user_type'] === '0' || $auth->output['user_type'] === '1')) {
    if (isset($_GET['remove']) && !empty($_POST['p_id'])) {
        if ($db->query("DELETE FROM `product` WHERE `id` = ?", $_POST['p_id'])){
            echo "success";
        } else {
            echo "Something Went Wrong!";
        }
        die();
    } else if (isset($_POST) && count($_POST) && isset($_FILES['product_p'])) {
        $title_arr = $_POST['data_title'];
        $value_arr = $_POST['data_value'];

        $itration = min(count($value_arr), count($title_arr));

        $data_arr = [];

        for ($i = 0; $i < $itration; $i++) {
            $data_arr[$title_arr[$i]] = $value_arr[$i];
        }

        $target_dir = "uploads/product/";
        $file_name = time() . rand(11, 99) . basename($_FILES["product_p"]["name"]);
        $target_file = $target_dir . $file_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["product_p"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        $ok = 0;
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["product_p"]["tmp_name"], $target_file)) {
                $ok = 1;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        if ($ok) {
            $desc_arr = json_encode($data_arr);

            $data_arr = [];
            $data_arr['name'] = $_POST['p_title'];
            $data_arr['desc'] = $desc_arr;
            $data_arr['poster'] = $file_name;
            $data_arr['added_by'] = $auth->output['id'];
            $data_arr['price'] = $_POST['p_price'];

            $query = $db->query("INSERT INTO `product`(`name`, `desc`, `added_by`,`price`, `poster`) VALUES (?,?,?,?,?)", array($data_arr['name'], $data_arr['desc'], $data_arr['added_by'], $data_arr['price'], $data_arr['poster']));

            if ($query) {
                echo "success";
            } else {
                echo "Something went wrong!";
            }
        } else {
            echo "Something Went Wrong";
        }
    } else {
        $files = [];
        header('HTTP/1.0 404 Not Found');

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
        new page($details, $files, $body, false);
    }
} else {
    $files = [];
    header('HTTP/1.0 404 Not Found');

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
    new page($details, $files, $body, false);
}


<?php
/** Loading all required files. */
require_once __DIR__ . '/includes/load.php';

$filter = new filter();
$url = new url();
$auth = new Authentication();
$db = new Database();


if ($filter->check_authentication() && $auth->getuserdata(NULL, $_COOKIE[SITE_NAME . '_AUTH_FILTER_FOR']) && ($auth->output['user_type'] === '0' || $auth->output['user_type'] === '1')) {
    $user_id = $auth->output['id'];
    $files = [
        'css' => '/assets/css/products.css',
        'js' => '/assets/js/products.js'
    ];

    $details = ['title'=> 'Edit Users'];

    $body = <<<EOPAGE
    <div class="container">
        <div class="action_bar">
            <button onclick="add_new_p()">ADD NEW</button>
        </div>
EOPAGE;
    $run = $db->query("SELECT * FROM `product` WHERE `removed` <> '1' ORDER BY `added_at` desc");
    $items = $run->fetchAll();

    if ($items && count($items)) {
        foreach ($items as $rand => $item) {
            /** right content */
            $body .= <<<EOPAGE
        <div class="product">
            <img src="{$url->home()}/uploads/product/{$item['poster']}" alt="400x400">
            <div class="title">
                <span>{$item['name']}</span>
            </div>
            <div class="actions">
                <button class="view" onclick="window.location.href = '{$url->home()}/post/{$item['id']}'">View</button>
                <button class="remove" data-post-id="{$item['id']}">Remove</button>
            </div>
        </div>
EOPAGE;
        }
    }

    $body .= <<<EOPAGE
    </div>
    <div class="new">
        <form id="product_form" onSubmit="return false;" class="form_new" method="POST" enctype="multipart/form-data">
            <img src="{$url->home()}/assets/images/400x400.png" id="img_p" style="height: 10rem; max-width: 20rem; overflow: hidden; border-radius: 10px;" alt="error">
            
            <input type="file" name="product_p" id="p_p">
            <input type="text" class="text-field" id="p_title" name="p_title" placeholder="Product name">
            <input type="text" class="text-field" id="p_price" name="p_price"  placeholder="price">
            
            <div class="data" id="data_container">
                <div class="box">
                    <input type="text" placeholder="Data Title" name="data_title[]" class="data_title">
                    <input type="text" placeholder="Data Name" name="data_value[]" class="data_value">
                </div>
                <div class="box">
                    <input type="text" placeholder="Data title" name="data_title[]" class="data_title">
                    <input type="text" placeholder="Data name" name="data_value[]" class="data_value">
                </div>
            </div>
            <div class="add">
                <button onclick="add_new_field()" type="button">Add New Field</button>
            </div>
            <div class="save">
                <button type="submit" name="submit" onclick="save_()" class="save_">Save</button>
            </div>
        </form>
    </div>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

EOPAGE;

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
}

new page($details, $files, $body, false);

<?php
/** Loading all required files. */
require_once __DIR__ . '/includes/load.php';

$filter = new filter();
$url = new url();
$auth = new Authentication();
$db = new Database();


if ($filter->check_authentication()) {
    $files = [
        'css' => '/assets/css/cart.css',
        'js' => '/assets/js/product.js'
    ];

    $auth->getuserdata(NULL, $_COOKIE[SITE_NAME . '_AUTH_FILTER_FOR']);
    $user_type = $auth->output['id'];

    $query = $db->query("SELECT * FROM `cart` WHERE `u_id` = ?", $user_type);

    if ($query->numRows()) {
        $products = $query->fetchAll();

        $body = <<<EOPAGE
        <div class="headingofcart">
            <h2>Shopping Cart</h2>
        </div>
EOPAGE;

        foreach ($products as $rand => $product) {
            $query = $db->query("SELECT * FROM `product` WHERE `id` = ?", $product['p_id']);

            $productData = $query->fetchArray();
            if (!empty($productData)) {
                $body .= <<<EOPAGE
        <section class="item1">
            <div class="page2sec1">
                <div class="p2leftside">
                    <img src="{$url->home()}/uploads/product/{$productData['poster']}" alt="error">
                </div>
                <div class="p2rightside">
                    <div class="headingofproduct">
                        <h2>{$productData['name']}</h2>
                    </div>
                    <div class="prices">
<!--                        <span class="priceold">Rs 4500/- </span>-->
                        <span class="pricenew">Rs {$productData['price']}₹/- </span>
                    </div>
                    <div class="detailbox">
EOPAGE;
                $product_details = json_decode($productData['desc'], true);

                $body .= '<div class="detailhead">';
                foreach ($product_details as $title => $detail) {
                    $body .= '<p class="detailhead">'.$title.'</p>';
                }
                $body .= '</div>';

                /**-----------------------------------------------**/
                $body .= '<div class="detailans">';
                foreach ($product_details as $title => $detail) {
                    $body .= '<p class="mainprop"> - '.$detail.' </p>';
                }
                $body .= '</div>';
                $body .= <<<EOPAGE
                    </div>
                    <div class="buttons">
                        <button class="BuyNow">Buy Now</button>
                        <button class="addtocart removefromcart" onclick="removeToCart({$productData['id']})">Remove Form Cart</button>
                    </div>
                    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                </div>
            </div>
        </section>
EOPAGE;
            }
        }
    } else {
        $body = <<<EOPAGE
        <style>@import "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css";</style>
        <div class="d-flex align-items-center justify-content-center" style="height: 50vh">
            <div class="text-center">
                <h1 class="display-1 fw-bold"></h1>
                <p class="fs-3"> <span class="text-danger">Opps!</span> Nothing in cart.</p>
                
                <a href="{$url->home()}" class="btn btn-primary">Go Home</a>
            </div>
        </div>
EOPAGE;

    }
    new page($details = ['title'=> 'Cart'], $files, $body, false);
} else {
    goto_login();
    die();
}
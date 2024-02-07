<?php
/** Loading all required files. */
require_once __DIR__ . '/includes/load.php';

$filter = new filter();
$url = new url();
$auth = new Authentication();
$db = new Database();

$details404 = [
    'title' => '404 page not found'
];
$error404 = <<<EOPAGE
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

$files = [];

if (strpos($_SERVER['REQUEST_URI'], 'post/')) {
    $post_id = ltrim($_SERVER['REQUEST_URI'], "post/");

    if(strpos($post_id, '/') || !is_numeric($post_id)) {
        header('HTTP/1.0 404 Not Found');

        $body = $error404;
        $details = $details404;
    } else {
        $run = $db->query("SELECT * FROM `product` WHERE `removed` <> '1' AND `id` = ?  ORDER BY `added_at` desc", $post_id);
        $items = $run->fetchArray();

        if (count($items)) {
            $details = [
                'title' => $items['name'],
            ];

            $files = [
                'css' => '/assets/css/product.css',
                'js' => '/assets/js/product.js',
                'custom_js' => <<<EOPAGE

EOPAGE

            ];

            $body = <<<EOPAGE
        <div class="headingofproduct">
            <h2>{$items['name']}</h2>
        </div>
        <section class="page2sec1">
            <div class="p2leftside">
                <img src="{$url->home()}/uploads/product/{$items['poster']}" alt="error">
            </div>
            <div class="p2rightside">
                <span class="pricenew">Rs {$items['price']}₹/- </span>
                <div class="detailbox">
EOPAGE;
            $product_details = json_decode($items['desc'], true);

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

            if ($filter->check_authentication()) {
                $auth->getuserdata(NULL, $_COOKIE[SITE_NAME . '_AUTH_FILTER_FOR']);
                $user_type = $auth->output['id'];

                $query = $db->query("SELECT * FROM `cart` WHERE `p_id` = ? AND `u_id` = ?", array($items['id'], $user_type));

                if (!$query->numRows()) {
                    $addToCartBtn = <<<EOPAGE
                    <button class="addtocart" onclick="addToCart({$items['id']})">Add To Cart</button>
EOPAGE;
                } else {
                    $addToCartBtn = <<<EOPAGE
                    <button class="addtocart" style="background: red" onclick="removeToCart({$items['id']})" id=>Remove from Cart</button>
EOPAGE;
                }
            } else {
                $addToCartBtn = <<<EOPAGE
                    <button class="addtocart" onclick="addToCart({$items['id']})">Add To Cart</button>
EOPAGE;
            }


            /** ---------- */
            $body .= <<<EOPAGE
                </div>
                <div class="buttons">
                    {$addToCartBtn}
                    <button class="BuyNow">Buy Now</button>
                </div>
                <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            </div>
        </section>
EOPAGE;


        } else {
            header('HTTP/1.0 404 Not Found');

            $body = $error404;
            $details = $details404;
        }
    }
} elseif (strpos($_SERVER['REQUEST_URI'], 'category/')) {
    $post_id = ltrim($_SERVER['REQUEST_URI'], "category/");

    $run = $db->query("SELECT `id` FROM `category` WHERE `type` = ? AND `parent` = ?", $post_id, 0);
    $items = $run->fetchArray();

    if (count($items)) {
        $details = [
            'title' => $post_id . ' &lsaquo; category'
        ];
        $files = [
            'custom_css' => <<<EOPAGE
    .headingofproduct {
          background: #007cff;
          color: white;
          height: 50px;
          line-height: 50px;
          margin: 21px 0px;
          justify-content: center;
          display: flex;
          margin: 25px auto 0px auto;
          width: 95%;
    }
EOPAGE

        ];
        $body = <<<EOPAGE
    <div class="headingofproduct">
        <h2 style="text-transform: capitalize">{$post_id}</h2>
    </div>
    <section class="purchasearea">
EOPAGE;

        /** left section */

        $run = $db->query("SELECT * FROM `product` WHERE JSON_SEARCH(category_id, 'all',?)", $items['id']);
        $items = $run->fetchAll();

        if ($items && count($items)) {
            $body .= <<<EOPAGE
        <section class="section2">
            <div class="section2A">
EOPAGE;
            foreach ($items as $rand => $item) {
                /** right content */
                $body .= <<<EOPAGE
            <div class="container">
                <img src="{$url->home()}/uploads/product/{$item['poster']}" alt="error">
                <h2 class="nameofitem">{$item['name']}</h2>
                <h2 class="priceofitem">price ₹ {$item['price']}</h2>
                <button class="buynowbutton" onclick="window.location.href = '{$url->home()}/post/{$item['id']}'">buy now</button>
            </div>
EOPAGE;
            }

            /** left section end */
            $body .= <<<EOPAGE
            </div>
        </section>
EOPAGE;
        } else {
            $body .= <<<EOPAGE
            <style>@import "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css";</style>
            <div class="d-flex align-items-center justify-content-center" style="height: 50vh;margin: 0 auto;">
                <div class="text-center">
                    <h1 class="display-1 fw-bold"></h1>
                    <p class="fs-3"> <span class="text-danger">Opps!</span> No Product found for {$post_id}.</p>
                    <p class="lead">
                        The product you’re looking for doesn’t found.
                      </p>
                    <a href="{$url->home()}" class="btn btn-primary">Go Home</a>
                </div>
            </div>
EOPAGE;

        }

        $body .= <<<EOPAGE
    </section>
EOPAGE;
    } else {
        header('HTTP/1.0 404 Not Found');

        $body = $error404;
        $details = $details404;
    }
} elseif (strpos($_SERVER['REQUEST_URI'], 'search/')) {
    $post_id = str_replace("/search/", "", $_SERVER['REQUEST_URI']);
    $details = [
        'title' => "Search Result for $post_id"
    ];
    $files = [
        'css' => '/assets/css/cart.css'
    ];

    $product_details = $db->query("SELECT * FROM `product` WHERE (`name` LIKE '%" . $post_id . "%')");

    $body = <<<EOPAGE
        <div class="headingofcart">
            <h2>Search Result for: {$post_id}</h2>
        </div>
EOPAGE;

    if ($product_details->numRows()) {
        $products = $product_details->fetchAll();
        foreach ($products as $rand => $product) {
            $body .= <<<EOPAGE
 <section class="item1">
            <div class="page2sec1">
                <div class="p2leftside">
                    <img src="{$url->home()}/uploads/product/{$product['poster']}" alt="error">
                </div>
                <div class="p2rightside">
                    <div class="headingofproduct">
                        <h2>{$product['name']}</h2>
                    </div>
                    <div class="prices">
<!--                        <span class="priceold">Rs 4500/- </span>-->
                        <span class="pricenew">Rs {$product['price']}₹/- </span>
                    </div>
<div class="detailbox">
EOPAGE;
            $product_details = json_decode($product['desc'], true);

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
                    </div>
                    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                </div>
            </div>
        </section>
EOPAGE;
        }
    } else {
        $body .= <<<EOPAGE
            <style>@import "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css";</style>
            <div class="d-flex align-items-center justify-content-center" style="height: 50vh;margin: 0 auto;">
                <div class="text-center">
                    <h1 class="display-1 fw-bold"></h1>
                    <p class="fs-3"> <span class="text-danger">Opps!</span> No Result found for {$post_id}.</p>
                    <p class="lead">
                        The product you’re looking for doesn’t found.
                      </p>
                    <a href="{$url->home()}" class="btn btn-primary">Go Home</a>
                </div>
            </div>
EOPAGE;
    }
}else {
    header('HTTP/1.0 404 Not Found');

    $body = $error404;
    $details = $details404;
}

/** Creating the page. */
new page($details, $files, $body, false);
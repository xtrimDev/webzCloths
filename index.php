<?php
/** Loading all required files. */
require_once __DIR__ . '/includes/load.php';

$filter = new filter();
$url = new url();
$auth = new Authentication();
$db = new Database();

$details = [
    'title' => 'Home',
];

$files = [];

$body = <<<EOPAGE
    <section class="section1">
EOPAGE;

$run = $db->query("SELECT * FROM `featured` WHERE `poster_type` = 'big'");
$Big_Poster = $run->fetchArray();

$run = $db->query("SELECT * FROM `featured` WHERE `poster_type` = 'small'");
$mid_Poster = $run->fetchArray();

$run = $db->query("SELECT * FROM `featured` WHERE `poster_type` = 'extra small'");
$small_Poster = $run->fetchArray();

if (is_array($Big_Poster) && count($Big_Poster)) {
   $body .= <<<EOPAGE
        <div class="sec1leftbox backimg1" style="background-image: url('{$url->home()}/uploads/featured/{$Big_Poster['poster']}')">
            <h3>{$Big_Poster['text']}</h3>
            <a href="">{$Big_Poster['description']}</a>
            <button class="readmorebtn" onclick="window.location.href = '/'">Read More</button>
        </div>
        <div class="sec1rightbox">
EOPAGE;

   if (is_array($mid_Poster) && count($mid_Poster)) {
       $body .= <<<EOPAGE
            <div class="sec1rightbox1 ">
                <img src="{$url->home()}/uploads/featured/{$mid_Poster['poster']}" alt="error">
                <a href="javascript:void(0)">"{$mid_Poster['text']}"</a>
            </div>
EOPAGE;
   }

    if (count($small_Poster)) {
        $body .= <<<EOPAGE
            <div class="sec1rightbox2">
                <div>
                    <img src="{$url->home()}/uploads/featured/{$small_Poster['poster']}" alt="error">
                </div>
                <div><a href="javascript:void(0)">"{$small_Poster['text']}"</a></div>
            </div>
EOPAGE;
    }

    $body .= <<<EOPAGE
        </div>
EOPAGE;
}

$body .= <<<EOPAGE
    </section>
    <section class="purchasearea">
EOPAGE;

/** left section */
$body .= <<<EOPAGE
        <section class="section2">
            <div class="section2A">
EOPAGE;

$run = $db->query("SELECT * FROM `product` WHERE `removed` <> '1' ORDER BY `added_at` desc");
$items = $run->fetchAll();

if ($items && count($items)) {
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
}

/** left section end */
$body .= <<<EOPAGE
            </div>
        </section>
EOPAGE;

/** right section */
$body .= <<<EOPAGE
        <div class="rightsidepannel">
EOPAGE;

/** left section end */
$body .= <<<EOPAGE
            <div class="sticky">
                <div class="short">
                    <div class="header">
                        <h3>Gender</h3>
                    </div>
                    <ul>
EOPAGE;
$run = $db->query("SELECT * FROM `category` WHERE `parent` = 0");
$Gender = $run->fetchAll();
foreach ($Gender as $rand => $type) {
    $body .= <<<EOPAGE
                <li onclick="window.location.href = '/category/{$type['type']}'">{$type['type']}</li>
EOPAGE;
}

$body .= <<<EOPAGE
                    </ul>
                </div>
                <div class="divider"></div>
            </div>
EOPAGE;

/** right section end */
$body .= <<<EOPAGE
        </div>
EOPAGE;

$body .= <<<EOPAGE
    </section>
EOPAGE;

/** Creating the page. */
new page($details, $files, $body, false);
<?php /** @noinspection ALL */

/**
 * This file used to create pages for admin panel.
 */

/** Loading all required files. */ 
require_once "config.php";
require_once "directory.php";
require_once "url.php";

/** This manages the page content. */
class page extends files_default
{
    private $url;
    private $directory;
    private $filter;
    private $Authentication;

    public function __construct(ARRAY $details, ARRAY $files = array(), STRING $body = '', BOOL $custom = false)
    {
        /** Getting all required things. */
        $this->url = new url();
        $this->directory = new dir();
        $this->filter  = new filter();
        $this->Authentication  = new Authentication();

        $title = ucwords(str_replace("-", " ", strtolower($details['title'])));

        /** HTML page starting. */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <title><?=$title?> &lsaquo; <?=ucfirst(SITE_NAME)?></title>
    <link rel="shortcut icon" href="<?=$this->url->home() . LOGO_URL?>" type="image/x-icon">
<?php
        /** Including all the css file form 'files_default' class. */
        if (isset($this->css) && !empty($this->css)) {
            if (is_array($this->css)) {
                if (isset($this->css['title'])) {
                    $no_of_css_files = count($this->css) - 1;
?>
    <!--<?=$this->css['title']?>-->
<?php
                } else {
                    $no_of_css_files = count($this->css);
                }
        
                for ($i = 0; $i < $no_of_css_files; $i++) {
?>
    <link rel="stylesheet" href="<?=$this->url->home() . $this->css[$i]?>">
<?php
                }
            } else {
?>
    <link rel="stylesheet" href="<?=$this->url->home() . $this->css?>">
<?php
            }
        } 

        /** Including all the js files form the working page. */
        if (isset($files['css']) && !empty($files['css'])) {
?>
    <!--Page Specific And CSS Libraies-->
<?php
            if (is_array($files['css']) && count($files['css']) > 0) {
                for ($i = 0; $i < count($files['css']); $i++) {
?>
    <link rel="stylesheet" href="<?=$this->url->home() . $files['css'][$i]?>">
<?php
                }
            } else {
?>
    <link rel="stylesheet" href="<?=$this->url->home() . $files['css']?>">
<?php
            }
        }
/* Including all the custom css files form the working page. */
if (!empty($files['custom_css']))
{
    ?>
    <!--Custom CSS codes-->
<?php
    if (is_array($files['custom_css']) && count($files['custom_css']) > 0)
    {
        for ($i = 0; $i < count($files['css']); $i++)
        {
?>
            <style>
                <?=$files['custom_css'][$i]?>
            </style>
<?php
        }
    } else {
?>
        <style><?=$files['custom_css']?></style>
<?php
    }
}
?>
</head>
<body>
<?php
    if (!$custom) {
?>
    <nav class="nav1">
        <div class="nav1box">
            <div class="nav1a">
                <img class="phoneimg" src="<?=$this->url->home()?>/assets/images/tele.png" alt="error">
                <a href="tel:2514369852">+91-<?=ADMIN_NUMBER?></a>
                <img class="mailimg" src="<?=$this->url->home()?>/assets/images/email.jpeg" alt="error">
                <a href="mailto:<?=ADMIN_EMAIL?>"><?=ADMIN_EMAIL?></a>
            </div>
            <div class="nav1b">
<?php
    if ($this->filter->check_authentication()) {
?>
                <img class="loginimg" src="<?=$this->url->home()?>/assets/images/user.png" alt="ERR">
<?php
    } else {
?>
            <a href="<?=$this->url->home()?>/login">Login</a>
            <span> / </span>
            <a href="<?=$this->url->home()?>/register">Register</a>
<?php
    }
?>
            </div>
        </div>
    </nav>
    <div class="nav2">
        <div class="nav2box">
            <div class="nav2a">
                <img class="companylogo" src="<?=$this->url->home()?>/assets/images/logo.png" alt="">
                <div class="div2a2">
                    <a class="nameofcompany" href="<?=$this->url->home()?>"><?=SITE_NAME?></a>
                    <a href="<?=$this->url->home()?>"><?=SITE_SLUG?></a>
                </div>
            </div>
            <div class="nav2b">
                <input class="searchbar1" id="search_value" type="search" placeholder="search items..">
                <img class="searchbtn1" id="searchbtn" src="<?=$this->url->home()?>/assets/images/search.jpeg" alt="Error">
            </div>
        </div>
    </div>
    <nav class="nav3">
        <div class="navlist">
            <ul>
<?php
                if (isset($this->ArrHeader) && !empty($this->ArrHeader) && is_array($this->ArrHeader) && count($this->ArrHeader) > 0) {
                    foreach ($this->ArrHeader as $title => $data) {
                        if (!array_key_exists('user_type', $data)) {
?>
                <li><a href="<?=$this->url->home() . $data['link']?>"><?=$title?></a></li>
<?php
                        } elseif (!$this->filter->check_authentication()) {
                           if($data['user_type'] == -1 ||array_key_exists('user_type', $data) && is_array($data['user_type']) && in_array('-1',$data['user_type'])) {
?>
                <li><a href="<?=$this->url->home() . $data['link']?>"><?=$title?></a></li>
<?php
                           }
                        } else {
                            $this->Authentication->getuserdata(NULL, $_COOKIE[SITE_NAME . '_AUTH_FILTER_FOR']);
                            $user_type = $this->Authentication->output['user_type'];
                            if($data['user_type'] == $user_type ||array_key_exists('user_type', $data) && is_array($data['user_type']) && in_array($user_type,$data['user_type'])) {
?>
                <li><a href="<?=$this->url->home() . $data['link']?>"><?=$title?></a></li>
<?php
                            }
                        }
                    }
                }
?>
            </ul>
        </div>
    </nav>
<?=$body?>
    <footer class="footer">
        <p> Copyright &copy; <?=SITE_PUBLISHED_YEAR?> <i><?=SITE_NAME?></i> All rights reserved.</p>
    </footer>
<?php
    } else {
        echo $body;
    }
        /** Including all the js file form 'files_default' class. */
        if (isset($this->js) && !empty($this->js)) {
            if (is_array($this->js)) {
                if (isset($this->js['title'])) {
                    $no_of_js_files = count($this->js) - 1;
?>
    <!--<?=$this->js['title']?>-->
<?php
                } else {
                    $no_of_js_files = count($this->js);
                }

                for ($i = 0; $i < $no_of_js_files; $i++) {
?>
    <script src="<?=$this->url->home() . $this->js[$i]?>"></script>
<?php
                }
            } else {
?>
    <script src="<?=$this->url->home() . $this->js?>"></script>
<?php
            }
        }

        /** Including all the js files form the working page. */
        if (isset($files['js']) && !empty($files['js'])) {
?>
    <!--Page Specific And JS Libraies-->
<?php
            if (is_array($files['js']) && count($files['js']) > 0) {
                for ($i = 0; $i < count($files['js']); $i++) {
?>
    <script src="<?=$this->url->home() . $files['js'][$i]?>"></script>
<?php
                }
            } else {
?>
    <script src="<?=$this->url->home() . $files['js']?>"></script>
<?php
            }
        }

/** Including all the js files form the working page. */
if (isset($files['custom_js']) && !empty($files['custom_js'])) {
?>
    <!--custom-->
<?php
    if (is_array($files['custom_js']) && count($files['custom_js']) > 0) {
        for ($i = 0; $i < count($files['custom_js']); $i++) {
?>
    <script>
<?=$files['custom_js'][$i]?>
    </script>
<?php
        }
    } else {
?>
    <script>
<?=$files['custom_js']?>
    </script>

<?php
    }
}
?>
</body>
</html>
<?php
    }
}
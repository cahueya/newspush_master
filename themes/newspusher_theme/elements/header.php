<?php defined('C5_EXECUTE') or die('Access Denied.');

$this->inc('elements/header_top.php');

$as = new GlobalArea('Header Search');
$blocks = $as->getTotalBlocksInArea();
?>

<header>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-xs-4 header-site-title" id="logo-area">
                <?php
                $a = new GlobalArea('Header Site Title');
                $a->display();
                ?>
            </div>

        </div>
    </div>
</header>

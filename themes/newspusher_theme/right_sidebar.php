<?php
defined('C5_EXECUTE') or die('Access Denied.');
$this->inc('elements/header.php');

$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
$u = $app->make(\Concrete\Core\User\User::class);
$dh = $app->make('helper/date'); 

$pkg = Package::getByHandle('newspush_master');
$activate_rest_api = $pkg->getConfig()->get('settings.newspusher.activate_rest_api');
$activate_telegram = $pkg->getConfig()->get('settings.newspusher.activate_telegram');
$activate_twitter  = $pkg->getConfig()->get('settings.newspusher.activate_twitter' );
$activate_facebook = $pkg->getConfig()->get('settings.newspusher.activate_facebook');
$activate_linkedin = $pkg->getConfig()->get('settings.newspusher.activate_linkedin');
$activate_sendy    = $pkg->getConfig()->get('settings.newspusher.activate_sendy'   );

$push_status_twitter  = $c->getAttribute('push_status_twitter' );
$push_status_telegram = $c->getAttribute('push_status_telegram');
$push_status_facebook = $c->getAttribute('push_status_facebook');
$push_status_linkedin = $c->getAttribute('push_status_linkedin');
$push_status_sendy    = $c->getAttribute('push_status_sendy'   );
$push_status_rest     = $c->getAttribute('push_status_rest'    );
$push_date            = $c->getAttribute('push_date'           );
$pushdatetime         = $dh->formatDateTime($value = $push_date, $format = 'long', $toTimezone = 'user');

?>

<main>
    <?php
    $a = new Area('Page Header');
    $a->display($c);
    ?>
    <div class="container">
        <table class="table table-bordered">
            <tr class="info">
                <td>
                    <?= t('Pushed at: ').$pushdatetime ?>
                </td>
                <?php if(!empty($activate_twitter)){ ?>
                    <td>
                    Twitter
                    <?php if($push_status_twitter == 3){ ?>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        <?php
                        } else { ?>
                            <i class="fa fa-times" aria-hidden="true"></i>
                        <?php
                    } ?>
                    </td>
                    <?php
                } ?>
                <?php if(!empty($activate_telegram)){ ?>
                    <td>
                    Telegram
                    <?php if($push_status_telegram == 3){ ?>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        <?php
                        } else {  ?>
                            <i class="fa fa-times" aria-hidden="true"></i>
                        <?php
                    }  ?>
                    </td>
                    <?php
                } ?>
                <?php if(!empty($activate_facebook)){ ?>
                    <td>
                    Facebook
                    <?php if($push_status_facebook == 3){ ?>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        <?php
                        } else {  ?>
                            <i class="fa fa-times" aria-hidden="true"></i>
                        <?php
                    } ?>
                    </td>
                    <?php
                } ?>
                <?php if(!empty($activate_sendy)){ ?>
                    <td>
                    Sendy
                    <?php if($push_status_sendy == 3){ ?>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        <?php
                        } else { ?>
                            <i class="fa fa-times" aria-hidden="true"></i>
                        <?php
                    } ?>
                    </td>
                    <?php
                } ?>
                <?php if(!empty($activate_rest_api)){ ?>
                    <td>
                    Rest API
                    <?php if($push_status_rest == 3){  ?>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        <?php
                        } else { ?>
                            <i class="fa fa-times" aria-hidden="true"></i>
                        <?php
                    } ?>
                    </td>
                    <?php
                } ?>
                <?php if(!empty($activate_linkedin)){ ?>
                    <td>
                    LinkedIn
                    <?php if($push_status_linkedin == 3){ ?>
                            <i class="fa fa-check" aria-hidden="true"></i>
                        <?php
                        } else { ?>
                            <i class="fa fa-times" aria-hidden="true"></i>
                        <?php
                    } ?>
                    </td>
                    <?php
                } ?>
            </tr>
        </table>
        <div class="row">
            <div class="col-sm-8 col-content itemscope" itemtype="https://schema.org/Article">
                <?php
                $a = new Area('Main');
                $a->setAreaGridMaximumColumns(12);
                $a->display($c);
                ?>
            </div>
            <div class="col-sm-4 col-sidebar">
                <?php
                $a = new Area('Sidebar');
                $a->setCustomTemplate('autonav', 'templates/sidebar_navigation.php');
                $a->display($c);

                $a = new Area('Sidebar Footer');
                $a->display($c);
                ?>
            </div>
        </div>
    </div>
    <?php
    $a = new Area('Page Footer');
    $a->enableGridContainer();
    $a->display($c);
    ?>
</main>
<?php
$this->inc('elements/alternativ-footer.php');

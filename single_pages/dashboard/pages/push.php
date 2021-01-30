<?php
defined('C5_EXECUTE') or die("Access Denied.");

use \Concrete\Core\Page\Page;
use \Concrete\Core\Support\Facade\Url;
use \Concrete\Core\Support\Facade\Config;

$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();

$form = $app->make('helper/form');
$ci = Loader::helper('concrete/ui');
$twitterurl = '<a href="https://apps.twitter.com" target="_blank">https://apps.twitter.com</a>';

$session = Core::make('app')->make('session');
foreach ($session->getFlashBag()->get('success', array()) as $success) {
    echo '<div class="alert alert-success" role="alert">';
        echo $success;
    echo '</div>';
}

foreach ($session->getFlashBag()->get('error', array()) as $error) {
    echo '<div class="alert alert-danger" role="alert">';
        echo $error;
    echo '</div>';
}
?>


    <form method="post" action="<?= $view->action('update_configuration')?>">
        <?php
        /** @var $token \Concrete\Core\Validation\CSRF\Token */
        echo $token->output('perform_update_configuration');

        ?>
        <div class="row">
            <div class="col-sm-3">
                <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="#overview" data-pane-toggle><?= t('Overview') ?></a></li>
                    <li><a href="#rest-api" data-pane-toggle><?= t('Concrete5 REST API') ?></a></li>
                    <li><a href="#twitter" data-pane-toggle><?= t('Twitter') ?></a></li>
                    <li><a href="#telegram" data-pane-toggle><?= t('Telegram') ?></a></li>
                    <li><a href="#facebook" data-pane-toggle><?= t('Facebook') ?></a></li>
                </ul>
            </div>



            <div class="col-sm-9 store-pane active" id="overview">
                <?php if(!empty($apiURL)&&!empty($api_pagepath)&&!empty($rest_client_id)&&!empty($rest_client_secret)){ ?>
                    <div class="alert alert-success" role="alert">
                        <?= t('REST API Credentials are set')?>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-danger" role="alert">
                        <?= t('REST API Credentials are missing')?>
                    </div>
                <?php } ?>

                <?php if(!empty($tw_consumerKey)&&!empty($tw_consumerSecret)&&!empty($tw_accessToken)&&!empty($tw_accessTokenSecret)){ ?>
                    <div class="alert alert-success" role="alert">
                        <?= t('Twitter Credentials are set')?>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-danger" role="alert">
                        <?= t('Twitter Credentials are missing')?>
                    </div>
                <?php } ?>

                <?php if(!empty($telegramBotToken)&&!empty($telegramChatID)){ ?>
                    <div class="alert alert-success" role="alert">
                        <?= t('Telegram Credentials are set')?>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-danger" role="alert">
                        <?= t('Telegram Credentials are missing')?>
                    </div>
                <?php } ?>
                <?php if(!empty($fb_app_id)&&!empty($fb_app_secret)&&!empty($fb_app_url)&&!empty($fb_app_long_page_token)){ ?>
                    <div class="alert alert-success" role="alert">
                        <?= t('Facebook Credentials are set')?>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-danger" role="alert">
                        <?= t('Facebook Credentials are missing')?>
                    </div>
                <?php } ?>
            </div><!-- #overview -->

            <div class="col-sm-9 store-pane" id="rest-api">
                <fieldset>
                    <div class="col-xs-12 col-md-12">     
                        <legend><?php echo t('REST API Data'); ?>
                            <span id="helpBlock" class="help-block">
                                Get yourself a REST API
                            </span>
                        </legend>  
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="apiURL"><?php echo t('URL of your API Client'); ?></label>
                            <?php echo $form->text('apiURL', $apiURL, array('class' => 'span2', 'placeholder'=>t('Client URL')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="api_pagepath"><?php echo t('Path under which to publish'); ?></label>
                            <?php echo $form->text('api_pagepath', $api_pagepath, array('class' => 'span2', 'placeholder'=>t('Page Path')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label for="rest_client_id"><?php echo t('Client ID'); ?></label>
                            <?php echo $form->text('rest_client_id', $rest_client_id, array('class' => 'span2', 'placeholder'=>t('Client ID')))?>
                        </div>
                    </div> 
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label for="rest_client_secret"><?php echo t('Client Secret'); ?></label>
                            <?php echo $form->text('rest_client_secret', $rest_client_secret, array('class' => 'span2', 'placeholder'=>t('Client Secret')))?>
                        </div>
                    </div>  
                </fieldset>
            </div><!-- #rest-api -->

            <div class="col-sm-9 store-pane" id="twitter">
                <fieldset>
                    <div class="col-xs-12 col-md-12">     
                        <legend><?php echo t('Twitter Subscription Data'); ?>
                            <span id="helpBlock" class="help-block"><?php echo t(
                                'You need to apply for a developer account at %s to get the access keys.', $twitterurl); ?></span>
                        </legend>  
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="tw_consumerKey"><?php echo t('Consumer Key'); ?></label>
                            <?php echo $form->text('tw_consumerKey', $tw_consumerKey, array('class' => 'span2', 'placeholder'=>t('Consumer Key')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="tw_consumerSecret"><?php echo t('Consumer Secret'); ?></label>
                            <?php echo $form->text('tw_consumerSecret', $tw_consumerSecret, array('class' => 'span2', 'placeholder'=>t('Consumer Secret')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="tw_accessToken"><?php echo t('Access Key'); ?></label>
                            <?php echo $form->text('tw_accessToken', $tw_accessToken, array('class' => 'span2', 'placeholder'=>t('Access Token')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="tw_accessTokenSecret"><?php echo t('Access Secret'); ?></label>
                            <?php echo $form->text('tw_accessTokenSecret', $tw_accessTokenSecret, array('class' => 'span2', 'placeholder'=>t('Access Token Secret')))?>
                        </div>
                    </div> 
                </fieldset>
            </div><!-- #twitter -->

            <div class="col-sm-9 store-pane" id="telegram">
                <fieldset>
                    <div class="col-xs-12 col-md-12">     
                        <legend><?php echo t('Telegram Data'); ?>
                            <span id="helpBlock" class="help-block"><?php echo t(
                                'You need to set up a Telegram Bot to get the token. Add the Bot to the group or channel of your choince and call it with "/start @BotName". After that, hit the "Get Chat ID button.'); ?></span>
                        </legend>  
                    </div> 
                    <div class="col-xs-12 col-md-8">
                        <div class="form-group">
                            <label for="telegramBotToken"><?php echo t('Telegram Token'); ?></label>
                            <?php echo $form->text('telegramBotToken', $telegramBotToken, array('maxlength' => '100', 'class' => 'span2', 'placeholder'=>t('Token')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="form-group">
                            <label for="telegramChatID"><?php echo t('Telegram Chat ID'); ?></label>
                            <?php echo $form->text('telegramChatID', $telegramChatID, array('maxlength' => '100', 'class' => 'span2', 'placeholder'=>t('Chat ID')))?>
                        </div>
                        <a href="<?= $this->action('getchatid') ?>" class="btn btn-default pull-left"><?= t('Get Chat ID') ?></a>
                    </div>  
                </fieldset>
            </div><!-- #telegram -->

            <div class="col-sm-9 store-pane" id="facebook">
                <fieldset>
                    <div class="col-xs-12 col-md-12">     
                        <legend><?php echo t('Facebook Data'); ?>
                            <span id="helpBlock" class="help-block"><?php echo t(
                                'You need to set up a Facebook developer account to get the data.'); ?></span>
                        </legend>  
                    </div> 
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="fb_app_id"><?php echo t('FB App ID'); ?></label>
                            <?php echo $form->text('fb_app_id', $fb_app_id, array('class' => 'span2', 'placeholder'=>t('FB App ID')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="fb_app_url"><?php echo t('URL of your FB Page'); ?></label>
                            <?php echo $form->text('fb_app_url', $fb_app_url, array('class' => 'span2', 'placeholder'=>t('URL of your FB Page')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label for="fb_app_secret"><?php echo t('FB App Secret'); ?></label>
                            <?php echo $form->text('fb_app_secret', $fb_app_secret, array('class' => 'span2', 'placeholder'=>t('FB App Secret')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label for="fb_app_pageid"><?php echo t('FB Page ID'); ?></label>
                            <?php echo $form->text('fb_app_pageid', $fb_app_pageid, array('class' => 'span2', 'placeholder'=>t('FB Page ID')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label for="fb_app_token"><?php echo t('FB access token'); ?></label>
                            <?php echo $form->text('fb_app_token', $fb_app_token, array('class' => 'span2', 'placeholder'=>t('FB access token')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <?php if (!empty($fb_app_long_page_token)) { ?>
                            <span class="label label-success">Page Token set</span>
                        <?php }else{ ?>
                            <span class="label label-danger">Page Token not set</span>
                        <?php } ?>

                        <?php if (!empty($fb_app_long_token)) { ?>
                            <span class="label label-success">User Token set</span>
                        <?php }else{ ?>
                            <span class="label label-danger">User Token not set</span>
                        <?php } ?>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="<?= $this->action('getfacebookauth') ?>" class="btn btn-default pull-right"><?= t('Get Authentication') ?></a>
                            <a href="<?= $this->action('flushtokens') ?>" class="btn btn-danger pull-right"><?= t('Flush Tokens') ?></a> 
                        </div>
                    </div>
                </fieldset>
            </div><!-- #facebook -->
        </div><!-- .row -->

        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <button class="pull-right btn btn-primary" type="submit"><?= t('Save') ?></button>
            </div>
        </div>
    </form>

<style>
    @media (max-width: 992px) {
        div#ccm-dashboard-content div.ccm-dashboard-content-full {
            margin-left: -20px !important;
            margin-right: -20px !important;
        }
    }

    .smallbreak {
       height: 10px;
        display: block;
        content: '';
    }
</style>

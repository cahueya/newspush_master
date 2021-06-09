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
                    <!--<li><a href="#linkedin" data-pane-toggle>--><?php // t('LinkedIn'); ?><!--</a></li>--> 
                    <li><a href="#sendy" data-pane-toggle><?= t('Sendy') ?></a></li>
                </ul>
            </div>

            <div class="col-sm-9 store-pane active" id="overview">
                <?php if(!empty($apiURL)&&!empty($api_pagepath)&&!empty($rest_client_id)&&!empty($rest_client_secret)&&!empty($activate_rest_api)){ ?>
                    <div class="alert alert-success" role="alert">
                        <?= t('REST API is active')?>
                    </div>
                <?php } else { ?>
                    <?php if(!empty($apiURL)&&!empty($api_pagepath)&&!empty($rest_client_id)&&!empty($rest_client_secret)){ ?>
                        <div class="alert alert-warning" role="alert">
                            <?= t('REST API Credentials are set')?>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= t('REST API Credentials are missing')?>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if(!empty($tw_consumerKey)&&!empty($tw_consumerSecret)&&!empty($tw_accessToken)&&!empty($tw_accessTokenSecret)&&!empty($activate_twitter)){ ?>
                        <div class="alert alert-success" role="alert">
                            <?= t('Twitter is active')?>
                        </div>
                <?php } else { ?>
                    <?php if(!empty($tw_consumerKey)&&!empty($tw_consumerSecret)&&!empty($tw_accessToken)&&!empty($tw_accessTokenSecret)){ ?>
                        <div class="alert alert-warning" role="alert">
                            <?= t('Twitter Credentials are set')?>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= t('Twitter Credentials are missing')?>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if(!empty($telegramBotToken)&&!empty($telegramChatID)&&!empty($activate_telegram)){ ?>
                    <div class="alert alert-success" role="alert">
                        <?= t('Telegram is active')?>
                    </div>
                <?php } else { ?> 
                    <?php if(!empty($telegramBotToken)&&!empty($telegramChatID)){ ?>
                        <div class="alert alert-warning" role="alert">
                            <?= t('Telegram Credentials are set')?>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= t('Telegram Credentials are missing')?>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if(!empty($fb_app_id)&&!empty($fb_app_secret)&&!empty($fb_app_long_page_token)&&!empty($activate_facebook)){ ?>
                    <div class="alert alert-success" role="alert">
                         <?= t('Facebook is active')?>
                    </div>
                <?php } else { ?>
                    <?php if(!empty($fb_app_id)&&!empty($fb_app_secret)&&!empty($fb_app_long_page_token)){ ?>
                        <div class="alert alert-warning" role="alert">
                            <?= t('Facebook Credentials are set')?>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= t('Facebook Credentials are missing')?>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php /** if(!empty($li_app_id)&&!empty($li_app_secret)&&!empty($activate_linkedin)){ ?>
                    <div class="alert alert-success" role="alert">
                         <?= t('LinkedIn is active')?>
                    </div>
                <?php } else { ?>
                    <?php if(!empty($li_app_id)&&!empty($li_app_secret)){ ?>
                        <div class="alert alert-warning" role="alert">
                            <?= t('LinkedIn Credentials are set')?>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= t('LinkedIn Credentials are missing')?>
                        </div>
                    <?php } ?>
                <?php } **/?>

                <?php if(!empty($sendy_url)&&!empty($sendy_apikey)&&!empty($sendy_listid)&&!empty($sendy_name_from)&&!empty($sendy_email_from)&&!empty($sendy_email_reply)&&!empty($activate_sendy)){ ?>
                    <div class="alert alert-success" role="alert">
                         <?= t('Sendy is active')?>
                    </div>
                <?php } else { ?>
                    <?php if(!empty($sendy_url)&&!empty($sendy_apikey)&&!empty($sendy_listid)&&!empty($sendy_name_from)&&!empty($sendy_email_from)&&!empty($sendy_email_reply)){ ?>
                        <div class="alert alert-warning" role="alert">
                            <?= t('Sendy Credentials are set')?>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-danger" role="alert">
                            <?= t('Sendy Credentials are missing')?>
                        </div>
                    <?php } ?>
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
                     <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label>
                                <input id="activate_rest_api" class="ccm-input-checkbox" type="checkbox" value="true" name="activate_rest_api" <?php if (isset($activate_rest_api) && $activate_rest_api == true) echo 'checked'; else if (!isset($activate_rest_api)) echo ' ' ?>>
                                <?= t('Activate'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="apiURL"><?php echo t('URL of your API Client'); ?></label>
                            <?php echo $form->url('apiURL', $apiURL, array('class' => 'span2', 'placeholder'=>t('Client URL')))?>
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
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label>
                                <input id="activate_twitter" class="ccm-input-checkbox" type="checkbox" value="true" name="activate_twitter" <?php if (isset($activate_twitter) && $activate_twitter == true) echo 'checked'; else if (!isset($activate_twitter)) echo ' ' ?>>
                                <?= t('Activate'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="tw_consumerKey"><?php echo t('API Key'); ?></label>
                            <?php echo $form->text('tw_consumerKey', $tw_consumerKey, array('class' => 'span2', 'placeholder'=>t('Consumer Key')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="tw_consumerSecret"><?php echo t('API Secret'); ?></label>
                            <?php echo $form->text('tw_consumerSecret', $tw_consumerSecret, array('class' => 'span2', 'placeholder'=>t('Consumer Secret')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="tw_accessToken"><?php echo t('Access Token'); ?></label>
                            <?php echo $form->text('tw_accessToken', $tw_accessToken, array('class' => 'span2', 'placeholder'=>t('Access Token')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="tw_accessTokenSecret"><?php echo t('Access Token Secret'); ?></label>
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
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label>
                                <input id="activate_telegram" class="ccm-input-checkbox" type="checkbox" value="true" name="activate_telegram" <?php if (isset($activate_telegram) && $activate_telegram == true) echo 'checked'; else if (!isset($activate_telegram)) echo ' ' ?>>
                                <?= t('Activate'); ?>
                            </label>
                        </div>
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
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label>
                                <input id="activate_facebook" class="ccm-input-checkbox" type="checkbox" value="true" name="activate_facebook" <?php if (isset($activate_facebook) && $activate_facebook == true) echo 'checked'; else if (!isset($activate_facebook)) echo ' ' ?>>
                                <?= t('Activate'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-5">
                        <div class="form-group">
                            <label for="fb_app_id"><?php echo t('FB App ID'); ?></label>
                            <?php echo $form->text('fb_app_id', $fb_app_id, array('class' => 'span2', 'placeholder'=>t('FB App ID')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-7">
                        <div class="form-group">
                            <label for="fb_app_secret"><?php echo t('FB App Secret'); ?></label>
                            <?php echo $form->text('fb_app_secret', $fb_app_secret, array('class' => 'span2', 'placeholder'=>t('FB App Secret')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">

                        <div class="form-group">
                            <label for="fb_app_token"><?php echo t('FB access token'); ?></label>
                            <span id="helpBlock" class="help-block">
                                <?php echo t('You need a page access token with the following permissions:'.'<code>pages_show_list</code>, <code>pages_read_engagement</code>,<code>pages_manage_metadata</code>,<code>pages_read_user_content</code>,<code>pages_manage_posts</code>,<code>
pages_manage_engagement</code>'); ?>
                            </span>
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

            <div class="col-sm-9 store-pane" id="linkedin">
                <fieldset>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label>
                                <input id="activate_linkedin" class="ccm-input-checkbox" type="checkbox" value="true" name="activate_linkedin" <?php if (isset($activate_linkedin) && $activate_linkedin == true) echo 'checked'; else if (!isset($activate_linkedin)) echo ' ' ?>>
                                <?= t('Activate'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="li_app_id"><?php echo t('Client ID'); ?></label>
                            <?php echo $form->text('li_app_id', $li_app_id, array('maxlength' => '100', 'class' => 'span2', 'placeholder'=>t('Client ID')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="li_app_secret"><?php echo t('Client Secret'); ?></label>
                            <?php echo $form->text('li_app_secret', $li_app_secret, array('maxlength' => '100', 'class' => 'span2', 'placeholder'=>t('Client Secret')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="form-group">
                            <label for="getlinkedinid"><?php echo t('LinkedIn ID'); ?></label>
                            <?php echo $form->text('linkedinid', $linkedinid, array('maxlength' => '100', 'class' => 'span2', 'placeholder'=>t('LinkedIn ID')))?>
                        </div>
                        <a href="<?= $this->action('getlinkedinid') ?>" class="btn btn-default pull-left"><?= t('LinkedIn ID') ?></a>
                        
                    </div>   
                    <div class="col-xs-12 col-md-4">
                        <?php if (!empty($linkedintoken)) { ?>
                            <span class="label label-success">Token set</span>
                        <?php }else{ ?>
                            <a href="<?= $this->action('getlinkedintoken') ?>" class="btn btn-default pull-right"><?= t('LinkedIn Token') ?></a>
                        <?php } ?>

                        
                    </div>
                </fieldset>
            </div><!-- #linkedin -->


            <div class="col-sm-9 store-pane" id="sendy">
                <fieldset>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label>
                                <input id="activate_sendy" class="ccm-input-checkbox" type="checkbox" value="true" name="activate_sendy" <?php if (isset($activate_sendy) && $activate_sendy == true) echo 'checked'; else if (!isset($activate_sendy)) echo ' ' ?>>
                                <?= t('Activate'); ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label for="sendy_url"><?php echo t('Sendy URL'); ?></label>
                            <?php echo $form->url('sendy_url', $sendy_url, array('class' => 'span2', 'placeholder'=>t('Sendy URL')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label for="sendy_apikey"><?php echo t('Sendy Api Key'); ?></label>
                            <?php echo $form->text('sendy_apikey', $sendy_apikey, array('maxlength' => '100', 'class' => 'span2', 'placeholder'=>t('Sendy Api Key')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="sendy_listid"><?php echo t('Sendy List ID'); ?></label>
                            <?php echo $form->text('sendy_listid', $sendy_listid, array('maxlength' => '100', 'class' => 'span2', 'placeholder'=>t('Sendy List ID')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="sendy_name_from"><?php echo t('Sendy From Name'); ?></label>
                            <?php echo $form->text('sendy_name_from', $sendy_name_from, array('maxlength' => '100', 'class' => 'span2', 'placeholder'=>t('Sendy From Name')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="sendy_email_from"><?php echo t('Sendy Email From'); ?></label>
                            <?php echo $form->email('sendy_email_from', $sendy_email_from, array('maxlength' => '100', 'class' => 'span2', 'placeholder'=>t('Sendy Email From')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <label for="sendy_email_reply"><?php echo t('Sendy Reply To Email'); ?></label>
                            <?php echo $form->email('sendy_email_reply', $sendy_email_reply, array('maxlength' => '100', 'class' => 'span2', 'placeholder'=>t('Sendy Reply To Email')))?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <?php
                               $editor = Core::make('editor');
                               $editor->getPluginManager()->deselect(array('table', 'underline', 'specialcharacters','stylescombo'));
                               $editor->getPluginManager()->select('fontsize');
                               echo $editor->outputStandardEditor('sendy_template_header', $sendy_template_header);
                            ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <?php
                               $editor = Core::make('editor');
                               $editor->getPluginManager()->deselect(array('table', 'underline', 'specialcharacters','stylescombo'));
                               $editor->getPluginManager()->select('fontsize');
                               echo $editor->outputStandardEditor('sendy_template_footer', $sendy_template_footer);
                            ?>
                        </div>
                    </div>
                </fieldset>
            </div><!-- #sendy -->












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

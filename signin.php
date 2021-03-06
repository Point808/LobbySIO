<?php

/*
 * Copyright (C) 2018 josh.north
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

    ini_set('session.gc_maxlifetime', 24*60*60);                                // MIN SESSION
    ini_set('session.gc_probability', 1);                                       // GC RATES
    ini_set('session.gc_divisor', 100);                                         // TIMES
    session_save_path('.tmp');                                                  // TEMP
    session_start();                                                            // START
    require_once __DIR__ . '/autoload.php';                                     // AUTOLOAD
    $StaticFunctions = new \App\LobbySIO\Misc\StaticFunctions();                // DEFAULT CLASSES
    $SiteInfo = new \App\LobbySIO\Database\SiteInfo();
    $Users = new \App\LobbySIO\Database\Users();
    if (isset($_SESSION['user_id'])) {                                          // LOGGED IN? GET USER OBJECT
        $session_user = $Users->getUserInfo($_SESSION['user_id'], "1", "0"); }
    if (isset($session_user)) {                                                 // GET UID OR SET TO KIOSK
        $uid = $session_user["0"]["users_id"];} else { $uid = "2"; }
    $app_disp_lang = filter_input(INPUT_COOKIE, 'app_disp_lang');               // SETUP LANGUAGE
    if(!isset($app_disp_lang)) {
        $app_disp_lang=$StaticFunctions->getDefaultLanguage(); }
    $siteidcookie = filter_input(INPUT_COOKIE, 'app_site');                     // SETUP SITE
    foreach($SiteInfo->getSite("0", $uid, "0", "0") as $arr) {
        $lookup_array[$arr['sites_id']]=1; }
        if(isset($lookup_array[$siteidcookie])) {
            $siteid = $siteidcookie; } else { $siteid = "1"; }
        if(!isset($siteid)) { $siteid="1"; }
    $Translate = new \App\LobbySIO\Language\Translate($app_disp_lang);          // SETUP TRANSLATOR
    $transLang =  $Translate->userLanguage();
    $VisitTypeInfo = new \App\LobbySIO\Database\VisitTypeInfo();
    $app_current_pagename = $transLang['SIGNIN'];                               // PAGE SETUP
    $app_current_pageicon = '<i class="fas fa-sign-in-alt"></i> ';
    require_once("inc/header.inc.php");
    if ($StaticFunctions->getSessionStatus() == true) {                         // CHECK STATUS
        header('Location: index.php');                                          // ELSE HOME
    } else { ?>
<!-- CONTENT START -->

        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <h2><i class="fas fa-sign-in-alt"></i> <?php echo $transLang['SIGNIN']; ?></h2>
                </div>
            </div>
            <form name="form-signin" class="form-signin" action="signin_2.php" method="post">
                <div class="row">
                    <div class="col-sm">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3"><?php echo $transLang['NAME']; ?>:</span>
                            </div>
                            <input type="text" id="firstname" name="firstname" class="form-control" placeholder="<?php echo $transLang['FIRST']; ?>" required autofocus>
                            <input type="text" id="lastname" name="lastname" class="form-control" placeholder="<?php echo $transLang['LAST']; ?>" required autofocus>
                        </div>
                    </div>
                </div>
    <?php if($SiteInfo->getSite($siteid, $uid, "0", "0")[0]["sites_region"] == "EMEA") { ?>
                <div class="row">
                    <div class="col-sm">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3"><?php echo $transLang['CARNUM']; ?></span>
                            </div>
                            <input type="text" id="company" name="carnum" class="form-control" placeholder="<?php echo $transLang['CARNUM']; ?>" required autofocus>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3"><?php echo $transLang['SSANUM']; ?></span>
                            </div>
                            <input type="text" id="company" name="ssanum" class="form-control" placeholder="<?php echo $transLang['SSANUM']; ?>" required autofocus>
                        </div>
                    </div>
                </div>
    <?php }; ?>
                <div class="row">
                    <div class="col-sm">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3"><?php echo $transLang['COMPANY']; ?></span>
                            </div>
                            <input type="text" id="company" name="company" class="form-control" placeholder="<?php echo $transLang['COMPANY']; ?>" required autofocus>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="hidden" name="siteid" id="siteid" value="<?php echo $siteid; ?>" />
                        <button type="submit" id="saveBtn" class="btn btn-lg btn-success btn-block" name="signin"><?php echo $transLang['NEXT']; ?></button>
                    </div>
                </div>
            </form>
        </div>

<!-- CONTENT END -->
<?php }; require_once("inc/footer.inc.php");

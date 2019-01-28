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

        <?php if (!empty($_POST)) {                              // PROCESS POST
            if (empty($_POST['carnum'])) { $carnum="";} else {$carnum=$_POST['carnum'];};
            if (empty($_POST['ssanum'])) { $ssanum="";} else {$ssanum=$_POST['ssanum'];};
            if (empty($_POST['firstname'])) { $firstname="";} else {$firstname=$_POST['firstname'];};
            if (empty($_POST['lastname'])) { $lastname="";} else {$lastname=$_POST['lastname'];};
            if (empty($_POST['company'])) { $company="";} else {$company=$_POST['company'];};
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <h2><i class="fas fa-sign-in-alt"></i> <?php echo $transLang['SIGNIN']; ?></h2>
                </div>
            </div>
            <form name="form-signin" class="form-signin" action="signin_3.php" method="post">
                <div class="row">
                    <div class="col-sm">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon3"><?php echo $transLang['REASON']; ?></span>
                            </div>
                            <select class="custom-select" id="visit_type" aria-label="Visit Type" name="visit_type" required>
                                <option value="" selected><?php echo $transLang['SELECTREASON']; ?></option><?php foreach($VisitTypeInfo->getVisitTypeInfo("%") as $row): ?>
                                <option value="<?php echo $row['visittypes_id']; ?>"><?php echo $transLang[$row['visittypes_name']]; ?></option><?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="hidden" name="carnum" id="carnum" value="<?php echo $carnum; ?>" />
                        <input type="hidden" name="ssanum" id="ssanum" value="<?php echo $ssanum; ?>" />
                        <input type="hidden" name="firstname" id="firstname" value="<?php echo $firstname; ?>" />
                        <input type="hidden" name="lastname" id="lastname" value="<?php echo $lastname; ?>" />
                        <input type="hidden" name="company" id="company" value="<?php echo $company; ?>" />
                        <button type="submit" id="saveBtn" class="btn btn-lg btn-success btn-block" name="signin"><?php echo $transLang['NEXT']; ?></button>
                    </div>
                </div>
            </form>
        </div>
        <?php } else {                                         // EXIT IF NO POST
        ?>
        <div class="container">
            <h2><?php echo $transLang['NOSIGNIN']; ?></h2>
        </div>
        <?php }; ?>

<!-- CONTENT END -->
<?php }; require_once("inc/footer.inc.php");

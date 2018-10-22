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
    $VisitActions = new \App\LobbySIO\Database\VisitActions();
    $app_current_pagename = $transLang['SIGNIN'];                               // PAGE SETUP
    $app_current_pageicon = '<i class="fas fa-file-signature"></i> ';
    require_once("inc/header.inc.php");
    if ($StaticFunctions->getSessionStatus() == true) {                         // CHECK STATUS
        header('Location: index.php');                                          // ELSE HOME
    } else { ?>
<!-- CONTENT START -->

        <?php if (!empty($_POST)) {                              // PROCESS POST
            if (empty($_POST['carnum'])) { $carnum="";} else {$carnum=$_POST['carnum'];};
            if (empty($_POST['ssanum'])) { $ssanum="";} else {$ssanum=$_POST['ssanum'];};
            echo $VisitActions->newVisit($_POST['firstname'], $_POST['lastname'], $_POST['company'], $_POST['visit_type'], $StaticFunctions->getUTC(), $_POST['v_signature'], $_POST['siteid'], "1", $_POST['e_signature'], $_POST['escort'], $carnum, $ssanum);
        ?>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h2><?php echo $transLang['SIGNIN_THANKYOU']; ?></h2>
                </div>
            </div>
            <div class="row">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th><?php echo $transLang['TIMEREASON']; ?></th><th><?php echo $transLang['COMPANY']; ?></th><th><?php echo $transLang['NAME']; ?></th><th><?php echo $transLang['ESCORT']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $timenow; ?><br><?php echo $transLang[$VisitTypeInfo->getVisitTypeInfo($_POST['visit_type'])[0]["visittypes_name"]]; ?></td><td><?php echo $_POST['company']; ?></td><td><?php echo $_POST['lastname']; ?>, <?php echo $_POST['firstname']; ?><br><img src="<?php echo $_POST['v_signature']; ?>" width="200" height="50" /></td><td><?php if (!empty($_POST['escort'])): echo $_POST['escort']; endif; ?><br /><?php if (!empty($_POST['e_signature'])): ?><img src="<?php echo $_POST['e_signature']; ?>" width="200" height="50" /><?php endif; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                        <?php if($SiteInfo->getSite($siteid, $uid, "0", "0")[0]["sites_region"] == "US") { echo "<p>" . $transLang['ACKNOWLEDGEMENT'] . "</p>"; } ?>
                        <p><?php echo $transLang['GDPR_TEXT']; ?><p>
            </div>
        </div>
        <?php } else {                                         // EXIT IF NO POST
        ?>
        <div class="container">
            <h2><?php echo $transLang['NOSIGNIN']; ?></h2>
        </div>
        <?php }; ?>
        <div class="container">
            <a href="index.php" class="btn btn-success btn-lg btn-block" tabindex="-1" role="button" aria-disabled="true">&nbsp<br /><?php echo $transLang['HOME']; ?><br />&nbsp</a>
        </div>

<!-- CONTENT END -->
<?php }; require_once("inc/footer.inc.php");

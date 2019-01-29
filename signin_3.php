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
            if (empty($_POST['visit_type'])) { $visit_type="";} else {$visit_type=$_POST['visit_type'];};
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <h2><i class="fas fa-sign-in-alt"></i> <?php echo $transLang['SIGNIN']; ?></h2>
                </div>
            </div>
            <form name="form-signin" class="form-signin" action="signin_4.php" method="post">
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0"><button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"><?php echo $transLang['ESECTION']; ?></button></h5>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3"><?php echo $transLang['ENAME']; ?>:</span>
                                    </div>
                                    <input type="text" id="escort" name="escort" class="form-control" placeholder="<?php echo $transLang['ETAG']; ?>" autofocus>
                                </div>
                                <h4><?php echo $transLang['ESIGNATURE']; ?>:</h4>
                                <div id="esignature-parent">
                                    <div id="esignature"></div>
                                </div>
                                <input type="hidden" name="e_signature" id="e_signature"></input>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h4><?php echo $transLang['VSIGNATURE']; ?>:</h4>
                        <div id="vsignature-parent">
                            <div id="vsignature"></div>
                        </div>
                        <input type="hidden" name="v_signature" id="v_signature" required />
                        <input type="hidden" name="siteid" id="siteid" value="<?php echo $siteid; ?>" />
                        <input type="hidden" name="carnum" id="carnum" value="<?php echo $carnum; ?>" />
                        <input type="hidden" name="ssanum" id="ssanum" value="<?php echo $ssanum; ?>" />
                        <input type="hidden" name="firstname" id="firstname" value="<?php echo $firstname; ?>" />
                        <input type="hidden" name="lastname" id="lastname" value="<?php echo $lastname; ?>" />
                        <input type="hidden" name="company" id="company" value="<?php echo $company; ?>" />
                        <input type="hidden" name="visit_type" id="visit_type" value="<?php echo $visit_type; ?>" />
                        <button type="submit" id="saveBtn" class="btn btn-lg btn-success btn-block" name="signin"><?php echo $transLang['NEXT']; ?></button>
                    </div>
                </div>
            </form>
        </div>
        <script>
            $(document).ready(function() {
                // Init jSignature for Escort field ONLY after we uncollapse the escort bootstrap div
                $('#collapseOne').on('shown.bs.collapse', function () {
                    var $esignature = $("#esignature").jSignature();
                    true
                    $('#esignature').change(function() {    
                        var data2 = $esignature.jSignature('getData');
                        $('#e_signature').val(data2);
                    });
                });
                // Init jSignature for Visitor field, onchange store in text field
                var $vsignature = $("#vsignature").jSignature();
                true
                $('#vsignature').change(function() {    
                    var data = $vsignature.jSignature('getData');
                   $('#v_signature').val(data);
                });
            });
            $("form").submit(function() {
                if($('#v_signature').val() == '') {
                    alert("<?php echo $transLang['SIGNATURE']; ?> <?php echo $transLang['REQUIRED']; ?>");
                    return false;
                }
                return true;
            });
        </script>
        <?php } else {                                         // EXIT IF NO POST
        ?>
        <div class="container">
            <h2><?php echo $transLang['NOSIGNIN']; ?></h2>
        </div>
        <?php }; ?>

<!-- CONTENT END -->
<?php }; require_once("inc/footer.inc.php");

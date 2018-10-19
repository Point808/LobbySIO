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

    //ini_set('session.gc_maxlifetime', 24*60*60);                              // MIN SESSION
    //ini_set('session.gc_probability', 1);                                     // GC RATES
    //ini_set('session.gc_divisor', 100);                                       // TIMES
    //session_save_path('.tmp');                                                // TEMP
    //session_start();                                                          // START
    require_once __DIR__ . '/../autoload.php';                                  // AUTOLOAD
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
    ob_start();                                                                 // OUTPUT BUFFER
    $session_status = $StaticFunctions->getSessionStatus();                     // SET A STATUS
    $defaulttimezone = $StaticFunctions->getDefaultTZ();
    date_default_timezone_set('UTC');                                           // DEFAULT TO UTC
    date_default_timezone_set($defaulttimezone);                                // UPDATE TO DEFAULT APP SETTING
    $timezone = $SiteInfo->getSite($siteid, $uid, "0", "0")[0]["sites_timezone"]; // GET TIMEZONE FROM SITE ID
    $timeplus = new DateTime($StaticFunctions->getUTC(), new DateTimeZone('UTC')); // DUMB WAY TO CALCULATE SOME TIMES
    $timeplus->setTimezone(new DateTimeZone("$timezone"));
    $timenow = $timeplus->format('Y-m-d H:i:s');
?>
<!doctype html>
<html lang="<?php echo $app_disp_lang; ?>">
<!-- HEADER CONTENT -->
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <?php if (basename($_SERVER['PHP_SELF']) == 'signin_display.php'): ?>
        <meta http-equiv="refresh" content="5; url=index.php" />
        <?php endif; ?>
        <?php if (basename($_SERVER['PHP_SELF']) == 'signout.php'): ?>
        <?php if (!empty($_POST['endvisit'])): ?>
        <meta http-equiv="refresh" content="5; url=index.php" />
        <?php endif; ?> 
        <?php endif; ?> 
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/sticky-footer-navbar.css">
        <link rel="stylesheet" href="css/all.min.css"/>
        <link rel="stylesheet" href="css/animate.min.css"/>
        <link rel="stylesheet" href="css/datatables.min.css" />
        <link rel="stylesheet" href="css/styles.css"/>
        <link rel="stylesheet" href="css/tempusdominus-bootstrap-4.min.css"/>
        <link rel="stylesheet" href="css/ie10-viewport-bug-workaround.css">
        <!-- [if lt IE 9]>
            <script src="js/html5shiv.js" type="text/javascript"></script>
            <script src="js/respond.min.js" type="text/javascript"></script>
        <![endif] -->
        <meta name="description" content="<?php echo $transLang['META_DESC']; ?>" />
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/datatables.min.js"></script>
        <script src="js/buttons.flash.min.js"></script>
        <script src="js/buttons.html5.min.js"></script>
        <script src="js/buttons.print.min.js"></script>
        <script src="js/dataTables.buttons.min.js"></script>
        <script src="js/jszip.min.js"></script>
        <script src="js/pdfmake.min.js"></script>
        <script src="js/vfs_fonts.js"></script>
        <script src="js/moment.min.js"></script>
        <script src="js/tempusdominus-bootstrap-4.min.js"></script>
        <script src="js/jSignature.min.js"></script>
        <title><?php echo $StaticFunctions->getTitle($app_current_pagename, $app_disp_lang); ?></title>
    </head>
    <body>
<!-- NAVBAR START -->
        <div class="container">
            <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="index.php"><img src="<?php echo $StaticFunctions->getLogo(); ?>" width="120" height="60" alt=""></a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar" aria-label="Toggle Navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <?php if ($session_status == true): ?>
                        <!-- MENU FOR ALL LOGGED IN -->
                        <li class="nav-item<?php if ($app_current_pagename==$transLang['HOME']): echo " active"; endif; ?>"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> <?php echo $transLang['HOME']; ?></a></li>
                        <li class="nav-item<?php if ($app_current_pagename==$transLang['ACCOUNT']): echo " active"; endif; ?>"><a class="nav-link" href="profile.php"><i class="fas fa-user-circle"></i> <?php echo $transLang['ACCOUNT']; ?></a></li>
                        <li class="nav-item<?php if ($app_current_pagename==$transLang['REPORTS']): echo " active"; endif; ?>"><a class="nav-link" href="reports.php"><i class="fas fa-chart-pie"></i> <?php echo $transLang['REPORTS']; ?></a></li>
                        <?php endif; ?>
                        <?php if (isset($session_user)) { if ($session_user["0"]["users_usertype"] == "ADMIN"): ?>
                        <!-- ADDITIONAL MENU IF LOGGED IN AS ADMIN -->
                        <li class="nav-item<?php if ($app_current_pagename==$transLang['ADMINISTRATION']): echo " active"; endif; ?>"><a class="nav-link" href="users.php"><i class="fas fa-users"></i> <?php echo $transLang['ADMINISTRATION']; ?></a></li>
                        <?php endif; }; ?>
                        <?php if ($session_status == false): ?>
                        <!-- MENU FOR ALL LOGGED OUT -->
                        <li class="nav-item<?php if ($app_current_pagename==$transLang['SIGNIN']): echo " active"; endif; ?>"><a class="nav-link" href="signin.php"><i class="fas fa-sign-in-alt"></i> <?php echo $transLang['SIGNIN']; ?></a></li>
                        <li class="nav-item<?php if ($app_current_pagename==$transLang['SIGNOUT']): echo " active"; endif; ?>"><a class="nav-link" href="signout.php"><i class="fas fa-sign-out-alt"></i> <?php echo $transLang['SIGNOUT']; ?></a></li>
                    </ul>
                    <ul class="navbar-nav mr-sm-2">
                        <li class="nav-item<?php if ($app_current_pagename==$transLang['LOGIN']): echo " active"; endif; ?>"><a class="nav-link btn btn-sm btn-outline-success" href="login.php"><i class="fas fa-cogs"></i> </a></li>
                        <?php endif; ?>
                        <?php if ($session_status == true): ?>
                            <!-- MENU FOR ALL LOGGED IN - BOTTOM END -->
                    </ul>
                    <ul class="navbar-nav mr-sm-2">
                        <li class="nav-item"><a class="nav-link<?php $sname=$SiteInfo->getSite($siteid, $uid, "0", "0")[0]["sites_name"]; if($sname=="NOSITE") { echo " btn btn-sm btn-outline-warning"; } else { echo " btn btn-sm btn-outline-secondary"; }; ?>" href="#" data-toggle="modal" data-target="#sitetimeModal"><i class="fas fa-map-marker-alt"></i> <?php if ($sname=="NOSITE") {echo $transLang['NOSITE'];} else { echo $sname; } ?></a></li>
                        <li class="nav-item<?php if ($app_current_pagename==$transLang['LOGOUT']): echo " active"; endif; ?>"><a class="nav-link btn btn-sm btn-outline-danger" href="logout.php"><i class="fas fa-ban"></i> <?php echo $transLang['LOGOUT']; ?></a></li>
                        <?php endif; ?>
                        <form action="changelang.php" method="post" name="changelang" class="changelang">
                            <div class="input-group mb-3">
                                <select class="form-control custom-select btn btn-outline-secondary" id="app_disp_lang" aria-label="Language" name="app_disp_lang">
                                    <?php foreach(glob('src/Language/*.ini') as $file){
                                        if(!is_dir($file)) { $filename=basename(preg_replace('/\.[^.]+$/','',preg_replace('/\.[^.]+$/','',$file))); }; ?>
                                    <option value="<?php echo $filename; ?>"<?php if ($filename==$app_disp_lang) { echo " selected"; }; ?>><?php echo strtoupper($filename); ?></option>
                                    <?php }; ?>
                                </select>
                            </div>
                        </form>
                    </ul>
                </div><!--/.nav-collapse -->
            </nav>
        </div>
        <!-- NAVBAR END -->
        <!-- MODAL START -->
        <div class="modal fade" id="sitetimeModal" tabindex="-1" role="dialog" aria-labelledby="Site" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="Site"><i class="fas fa-map-marker-alt"></i> <?php echo $transLang['SITE']; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-inline my-2 my-lg-0" action="changesite.php" method="post">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button"><?php echo $transLang['CHOOSE']; ?></button>
                                </div>
                                <select class="custom-select" id="site" aria-label="Site" name="site" required>
                                    <option value="<?php if (isset($siteid)) { echo $SiteInfo->getSite($siteid, $uid, "0", "0")[0]["sites_name"]; } else { echo ""; } ?>" selected><?php if (isset($siteid)) { if ($SiteInfo->getSite($siteid, $uid, "0", "0")[0]["sites_name"]=="NOSITE") {echo $transLang['NOSITE'];} else { echo $SiteInfo->getSite($siteid, $uid, "0", "0")[0]["sites_name"]; } } else { echo "- - -"; } ?></option>
                                   <?php foreach($SiteInfo->getSite("0", $uid, "0", "0") as $row): ?>
                                   <option value="<?php echo $row['sites_id']; ?>"><?php if ($row['sites_name']=="NOSITE") {echo $transLang['NOSITE'];} else { echo $row['sites_name']; } ?></option>
                                   <?php endforeach; ?>
                                </select>
                                <input class="btn" type="submit" value="<?php echo $transLang['SAVE']; ?>" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(".changelang").change(function(e){ 
                e.preventDefault();
                $(this).closest("form").submit();
            });
        </script>
        <script>
            $(document).ready(function () {
            //POP MODAL IF NO COOKIE
                if ( document.cookie.indexOf("app_site=") < 0) {
                    $("#sitetimeModal").modal("show");
                }
            });
        </script>
        <!-- MODAL END -->

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
    $VisitInfo = new \App\LobbySIO\Database\VisitInfo();
    $VisitActions = new \App\LobbySIO\Database\VisitActions();
    $app_current_pagename = $transLang['SIGNOUT'];                              // PAGE SETUP
    $app_current_pageicon = '<i class="fas fa-sign-out-alt"></i> ';
    require_once("inc/header.inc.php");
    if ($StaticFunctions->getSessionStatus() == true) {                         // CHECK STATUS
        header('Location: index.php');                                          // ELSE HOME
    } else { ?>
<!-- CONTENT START -->

        <?php 
            if (!empty($_POST['endvisit'])) {                    // PROCESS POST
            echo $VisitActions->endVisit($_POST['endvisit'], $StaticFunctions->getUTC());
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <h2><?php echo $transLang['SIGNOUT_THANKYOU']; ?></h2>
                </div>
            </div>
        </div>            
        <?php } else {                                           // OR SHOW LIST
            $approval = "2";                                     // ONLY SHOW APPROVED
            $page_num = 1;                                       // PAGINATION
            if(!empty($_GET['pnum'])):
            $page_num = filter_input(INPUT_GET, 'pnum', FILTER_VALIDATE_INT);
            if(false === $page_num):
            $page_num = 1;
            endif;
            endif;
            $offset = ($page_num - 1) * $StaticFunctions->getPageRows();
            $row_count = count($VisitInfo->getVisitInfo($siteid, $approval, "empty", "%", "%", "%", "%", "%", "%"));
            $page_count = 0;
            if (0 === $row_count): else: $page_count = (int)ceil($row_count / $StaticFunctions->getPageRows()); if($page_num > $page_count): $page_num = 1; endif; endif;
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <h2><i class="fas fa-sign-out-alt"></i> <?php echo $transLang['SIGNOUT']; ?></h2>
                </div>
                <div class="col-sm">
                    <a href="index.php" class="btn btn-info btn-block" tabindex="-1" role="button" aria-disabled="true"><?php echo $transLang['BACK']; ?></a>
                </div>
            </div>
            <form class="form-signout" method="post" onsubmit="return confirm('<?php echo $transLang['END_VISIT_WARNING']; ?>')">
                <?php echo '<ul class="pagination pagination-sm"><li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">'.$transLang['PAGE'].'</a></li>'; for ($i = 1; $i <= $page_count; $i++): echo '<li class="page-item'; if ($i === $page_num): echo ' active'; else: echo ' '; endif; echo '"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?pnum=' . $i . '">' . $i . '</a></li>'; endfor; echo '</ul>'; ?>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr><th><?php echo $transLang['BADGE']; ?></th><th><?php echo $transLang['IN']; ?></th><th><?php echo $transLang['NAME']; ?></th><th><?php echo $transLang['ESCORT']; ?></th><th><?php echo $transLang['ACTIONS']; ?></th></tr>
                    </thead>
                    <tbody>
                        <?php $approval="2"; foreach ($VisitInfo->getVisitInfo($siteid, $approval, "empty", "%", "%", "%", "%", $StaticFunctions->getPageRows(), $offset) as $row):
                            $timein = new DateTime($row['visits_intime'], new DateTimeZone('UTC'));
                            $timein->setTimezone(new DateTimeZone("$timezone"));
                            $timein_disp = $timein->format('Y-m-d H:i:s');
                        ?>
                        <tr><td><?php echo $row['visits_badge']; ?></td><td><?php echo $timein_disp; ?></td><td><?php echo $row['visits_lastname'] . ", " . $row['visits_firstname']; ?><br /><img src="<?php echo $row['visits_signature']; ?>" width="200" height="50"></img></td><td><?php if (!empty($row['visits_escort'])) {echo $row['visits_escort'] . '<br /><img src="' . $row['visits_escort_signature'] . '" width="200" height="50"></img>'; } ?></td><td><nobr><button type="submit" name="endvisit" value="<?php echo $row['visits_id']; ?>" class="btn btn-warning btn-lg btn-block"><i class="fas fa-sign-out-alt"></i><?php echo $transLang['SIGNOUT']; ?></button> </nobr></td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        </div>
        <?php }; ?>

<!-- CONTENT END -->
<?php }; require_once("inc/footer.inc.php");

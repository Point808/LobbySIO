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
    $VisitTypeInfo = new \App\LobbySIO\Database\VisitTypeInfo();                // ADDITIONAL CLASSES
    $IDTypeInfo = new \App\LobbySIO\Database\IDTypeInfo();
    $VisitInfo = new \App\LobbySIO\Database\VisitInfo();
    $VisitActions = new \App\LobbySIO\Database\VisitActions();
    $app_current_pagename = $transLang['HOME'];                                 // PAGE SETUP
    $app_current_pageicon = '<i class="fas fa-home"></i> ';
    require_once("inc/header.inc.php");
    if ($StaticFunctions->getSessionStatus() == false) {                        // CHECK STATUS
    ?>
<!-- GUEST CONTENT START -->
                    
                    <div class="jumbotron vertical-center">
    <div class="container">
      <div class="row">
        <div class="col-sm">
            &nbsp;
        </div>
        <div class="col-sm">
            <button class="btn btn-outline-danger btn-lg btn-block" tabindex="-1" role="button" aria-disabled="true" disabled><i class="fas fa-4x fa-sign-in-alt"></i><img src="<?php echo $StaticFunctions->getLogoText(); ?>" height="140" width="370"></img><br /><h1><?php echo $transLang['APP_NAME']; ?></h1></button>
        </div>
        <div class="col-sm">
            &nbsp;
        </div>
      </div>

      <div class="row">
          <div class="col-sm"><hr /></div>
      </div>
      <div class="row">
        <div class="col-sm">
          <a href="signin.php" class="btn btn-success btn-lg btn-block" tabindex="-1" role="button" aria-disabled="true">&nbsp<br />&nbsp<br /><i class="fas fa-sign-in-alt"></i><br /><?php echo $transLang['CUSTSIGNIN']; ?><br />&nbsp<br />&nbsp</a>
        </div>
        <div class="col-sm">
          <a href="signout.php" class="btn btn-info btn-lg btn-block" tabindex="-1" role="button" aria-disabled="true">&nbsp<br />&nbsp<br /><i class="fas fa-sign-out-alt"></i><br /><?php echo $transLang['CUSTSIGNOUT']; ?><br />&nbsp<br />&nbsp</a>
        </div>
      </div>
    </div>
</div>

<!-- GUEST CONTENT END -->
    <?php
    } else { 
    ?>
<!-- USER CONTENT START -->

    <?php 
    if (!empty($_POST['endvisit'])) {
    if (!empty($_POST['outtime'])) {
    $newdate = new DateTime($_POST['outtime'], new DateTimeZone($timezone));
    $newdate->setTimeZone(new DateTimeZone('UTC'));
    $postdate=$newdate->format('Y-m-d H:i:s');
    echo $VisitActions->endVisit($_POST['endvisit'], $postdate);
    } else {
        echo $VisitActions->endVisit($_POST['endvisit'], $StaticFunctions->getUTC());
    }
    }

    if (!empty($_POST['voidvisit'])) {
    echo $VisitActions->voidVisit($_POST['voidvisit'], "0");
    }



    // If post is approved, save after error checking.
    if (!empty($_POST['approvevisit'])) {
        if (empty($_POST['id_type'])) { $id_type_error="1"; }
        else { $id_type_error="0";
            if (empty($_POST['badge'])) { $badge_error="1"; }
            else { $badge_error="0";
                if (empty($_POST['initials'])) { $initials_error="1"; }
                else { $initials_error="0";
                    if (isset($_POST['id_checked']) && $_POST['id_checked'] == '1') { $id_checked="1"; }
                    else { $id_checked="0"; }
                    if (isset($_POST['citizen']) && $_POST['citizen'] == '1') { $citizen="1"; }
                    else { $citizen="0"; }
                    $approved="2";
                    echo $VisitActions->approveVisit($_POST['approvevisit'], $_POST['id_type'], $id_checked, $citizen, $_POST['badge'], $_POST['initials'], $approved);
                    }
                }
            }
        }

    // check all unapproved or approved
    $approval = "1";

    // Set up pagination
    $page_num = 1;
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
                        <h2><i class="fas fa-home"></i> <?php echo $transLang['ACTIVEVISITS']; ?></h2>
                    </div>
                    <div class="col-sm">
                        <input type="button" class="btn btn-success btn-lg btn-block" onClick="window.location.reload()" value="<?php echo $transLang['REFRESH']; ?>">
                    </div>
                </div>
                <?php echo '<ul class="pagination pagination-sm"><li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">' . $transLang['PAGE'] . '</a></li>'; for ($i = 1; $i <= $page_count; $i++): echo '<li class="page-item'; if ($i === $page_num): echo ' active'; else: echo ' '; endif; echo '"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?pnum=' . $i . '">' . $i . '</a></li>'; endfor; echo '</ul>'; ?>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th><?php echo $transLang['TIMEREASON']; ?></th><th><?php echo $transLang['NAME']; ?></th><th><?php echo $transLang['ESCORT']; ?></th><th><?php echo $transLang['VALIDATIONS']; ?></th><th><?php echo $transLang['BADGEINITIALS']; ?></th><?php if($SiteInfo->getSite($siteid, $uid, "0", "0")[0]["sites_region"] == "EMEA") { ?><th><?php echo $transLang['CARNUM'] . " / " . $transLang['SSANUM']; ?></th><?php }; ?><th><?php echo $transLang['ACTIONS']; ?></th><th>&nbsp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($VisitInfo->getVisitInfo($siteid, $approval, "empty", "%", "%", "%", "%", $StaticFunctions->getPageRows(), $offset) as $row):
                        $visitid = $row['visits_id'];
                        $timein = new DateTime($row['visits_intime'], new DateTimeZone('UTC'));
                        $timein->setTimezone(new DateTimeZone("$timezone"));
                        $timein_disp = $timein->format('Y-m-d H:i:s');
                        if(!empty($row['visits_carnum'])) { $carnum=$row['visits_carnum']; } else { $carnum="";};
                        if(!empty($row['visits_ssanum'])) { $ssanum=$row['visits_ssanum']; } else { $ssanum="";};
                        ?>
                        <?php if($row['visits_approved']==2) { ?>
                        <tr class="alert alert-success">
                        <?php } else { ?>
                        <tr class="alert alert-warning">
                        <?php }; ?>
                            <form class="form-approve" method="post">
                                <td><?php echo $timein_disp; ?><br><?php echo $transLang[$VisitTypeInfo->getVisitTypeInfo($row['visits_reason'])[0]["visittypes_name"]]; ?></td>
                                <td><?php echo $row['visits_lastname'] . ", " . $row['visits_firstname']; ?><br><img src="<?php echo $row['visits_signature']; ?>" width="200" height="50"></img></td>
                                <td><?php if (!empty($row['visits_escort'])) {echo $row['visits_escort'] . '<br /><img src="' . $row['visits_escort_signature'] . '" width="200" height="50"></img>'; } ?></td>
                                <td>
                                    <?php if($row['visits_approved']==2) { ?>
                                    <input class="form-control" type="text" id="id_type" name="id_type" disabled value="<?php echo $transLang[$IDTypeInfo->getIDTypeInfo($row['visits_id_type'])[0]["idtypes_name"]]; ?>">
                                    <?php if($row['visits_id_checked']==1) { ?>
                                    <input class="form-check-input" type="checkbox" value="1" id="id_checked" name="id_checked" checked disabled>
                                    <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" value="1" id="id_checked" name="id_checked" disabled>
                                    <?php }; ?>
                                    <label class="form-check-label" for="id_checked"><?php echo $transLang['ID_CHECKED']; ?></label><br>
                                    <?php if($SiteInfo->getSite($siteid, $uid, "0", "0")[0]["sites_region"] == "US") { if($row['visits_citizen']==1) { ?>
                                    <input class="form-check-input" type="checkbox" value="1" id="citizen" name="citizen" checked disabled>
                                    <?php } else { ?>
                                    <input class="form-check-input" type="checkbox" value="1" id="citizen" name="citizen" disabled>
                                    <?php }; ?>
                                    <label class="form-check-label" for="citizen"><?php echo $transLang['CITIZEN']; ?></label>
                                                                    <?php }; ?>
</td>
                                <td><input type="text" id="badge" name="badge" class="form-control" autofocus disabled value="<?php echo $row['visits_badge']; ?>"> <input type="text" id="initials" name="initials" class="form-control" autofocus disabled value="<?php echo $row['visits_initials']; ?>"></td>
<?php if($SiteInfo->getSite($siteid, $uid, "0", "0")[0]["sites_region"] == "EMEA") { ?>
                                <td><?php echo $carnum; ?> / <?php echo $ssanum; ?></td>
<?php }; ?>
                                <td> </td>
                                <td><button type="submit" name="endvisit" value="<?php echo $row['visits_id']; ?>" class="btn btn-warning btn-block"><i class="fas fa-sign-out-alt"></i>&nbsp<?php echo $transLang['SIGNOUT']; ?></button><br>
                                    <div>
                                        <div class="row">
                                            <div class="col-lg">
                                                <input placeholder="<?php echo $transLang['OPTIONAL']; ?>" name="outtime" type="text" class="form-control datetimepicker-input datetimepicker-<?php echo $row['visits_id']; ?>" id="datetimepicker-<?php echo $row['visits_id']; ?>" data-toggle="datetimepicker" data-target=".datetimepicker-<?php echo $row['visits_id']; ?>"/>
                                            </div>
                                            <script type="text/javascript">
                                                $(function () {
                                                    $('.datetimepicker-<?php echo $row['visits_id']; ?>').datetimepicker({'timeZone': '<?php echo $timezone; ?>', 'sideBySide':true, 'format':'YYYY-MM-DD HH:mm:ss'});
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </td>
                                <?php } else { ?>
                                <select class="custom-select<?php if( isset($id_type_error) && $id_type_error == "1" && $_POST['approvevisit'] == $visitid ) { echo " is-invalid"; } ?>" id="id_type" aria-label="ID Type" name="id_type">
                                    <option value="" selected><?php echo $transLang['SELECTID']; ?></option><?php foreach($IDTypeInfo->getIDTypeInfo("%") as $row): ?>
                                    <option value="<?php echo $row['idtypes_id']; ?>"><?php echo $transLang[$row['idtypes_name']]; ?></option><?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback"><?php echo $transLang['REQUIRED']; ?></div>
                                <input class="form-check-input" type="checkbox" value="1" id="id_checked" name="id_checked">
                                <label class="form-check-label" for="id_checked"><?php echo $transLang['ID_CHECKED']; ?></label><br>
<?php if($SiteInfo->getSite($siteid, $uid, "0", "0")[0]["sites_region"] == "US") { ?>
                                <input class="form-check-input" type="checkbox" value="1" id="citizen" name="citizen">
                                <label class="form-check-label" for="citizen"><?php echo $transLang['CITIZEN']; ?></label>
<?php }; ?>
                                <td>
                                    <input type="text" id="badge" name="badge" class="form-control<?php if( isset($badge_error) && $badge_error == "1" && $_POST['approvevisit'] == $visitid ) { echo " is-invalid"; } ?>" placeholder="<?php echo $transLang['BADGE']; ?>" autofocus maxlength="15">
                                        <div class="invalid-feedback"><?php echo $transLang['REQUIRED']; ?></div>
                                    <input type="text" id="initials" name="initials" class="form-control<?php if( isset($initials_error) && $initials_error == "1" && $_POST['approvevisit'] == $visitid ) { echo " is-invalid"; } ?>" placeholder="<?php echo $transLang['INITIALS']; ?>" autofocus maxlength="5">
                                        <div class="invalid-feedback"><?php echo $transLang['REQUIRED']; ?></div>
                                </td>
<?php if($SiteInfo->getSite($siteid, $uid, "0", "0")[0]["sites_region"] == "EMEA") { ?>
                                <td><?php echo $carnum; ?> / <?php echo $ssanum; ?></td>
<?php }; ?>
                                <td>
                                    <button type="submit" name="approvevisit" value="<?php echo $visitid; ?>" class="btn btn-success btn-block"><i class="fas fa-thumbs-up"></i>&nbsp;<?php echo $transLang['APPROVE']; ?></button><br /><button type="submit" name="voidvisit" value="<?php echo $visitid; ?>" class="btn btn-danger btn-block" onsubmit="return confirm('<?php echo $transLang['VOID_WARNING']; ?>')"><i class="fas fa-thumbs-down"></i>&nbsp;<?php echo $transLang['VOID']; ?></button>
                                </td>
                                <td>
                                    <button type="submit" name="endvisit" value="<?php echo $visitid; ?>" class="btn btn-warning btn-block"><i class="fas fa-sign-out-alt"></i>&nbsp;<?php echo $transLang['SIGNOUT']; ?></button>
                                    <br>
                                    <div>
                                        <div class="row">
                                            <div class="col-lg">
                                                <input name="outtime" type="text" class="form-control datetimepicker-input datetimepicker-<?php echo $visitid; ?>" id="datetimepicker-<?php echo $visitid; ?>" data-toggle="datetimepicker" data-target=".datetimepicker-<?php echo $visitid; ?>" />
                                            </div>
                                            <script type="text/javascript">
                                                $(function () {
                                                    $('.datetimepicker-<?php echo $visitid; ?>').datetimepicker({'sideBySide':true, 'format':'YYYY-MM-DD HH:mm:ss'});
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </td>
                                <?php }; ?>
                            </form>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

<!-- USER CONTENT END -->
<?php }; require_once("inc/footer.inc.php");

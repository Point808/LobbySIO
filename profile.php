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

    ini_set('session.gc_maxlifetime', 24*60*60);                 // MIN SESSION
    ini_set('session.gc_probability', 1);                        // GC RATES
    ini_set('session.gc_divisor', 100);                          // TIMES
    session_save_path('.tmp');                                   // TEMP
    session_start();                                             // START
    require_once __DIR__ . '/autoload.php';                      // AUTOLOAD
    $StaticFunctions = new \App\LobbySIO\Misc\StaticFunctions(); // CLASSES
    $Users = new \App\LobbySIO\Database\Users();
    if(!isset($_COOKIE['app_disp_lang'])) { $app_disp_lang = $StaticFunctions->getDefaultLanguage(); } else { $app_disp_lang = $_COOKIE['app_disp_lang']; };
    $Translate = new \App\LobbySIO\Language\Translate($app_disp_lang);
    $transLang =  $Translate->userLanguage();                    // SETUP TRANSLATOR
    $app_current_pagename = $transLang['ACCOUNT'];               // PAGE FUNCTION
    $app_current_pageicon = '<i class="fas fa-user-circle"></i> ';// PAGE ICON
    require_once("inc/header.inc.php");                          // SHOW HEADER
    if ($StaticFunctions->getSessionStatus() == false) {         // CHECK STATUS
        echo $StaticFunctions->killSession();                    // ELSE DIE
    } else { ?>
<!-- CONTENT START -->

        <?php
            $minpasslength = $StaticFunctions->getMinPass();
            if (isset($_POST['saveprofile'])):
                if (empty($_POST['password']) && empty($_POST['newpassword2'])):
                    $Users->setUserInfo($session_user["0"]["users_id"], $_POST['firstname'], $_POST['lastname'], $_POST['email'], $session_user["0"]["users_usertypeid"], $session_user["0"]["users_password"]);
                    header('Location: ' . $_SERVER['PHP_SELF']);
            elseif (strlen($_POST['password']) < $minpasslength):
            echo "Password must be at least $minpasslength characters.";
            elseif (!empty($_POST['password']) && empty($_POST['newpassword2'])):
            echo "Please confirm password if you wish to change it";
            elseif ($_POST['password'] != $_POST['newpassword2']):
            echo "New passwords do not match";
            elseif (!empty($_POST['password']) && ($_POST['password'] = $_POST['newpassword2'])):
            // change pass
            require_once("src/Misc/PasswordHash.php");
                $hasher = new PasswordHash(8, FALSE);
                $password = $hasher->HashPassword($_POST['password']);
                    $Users->setUserInfo($session_user["0"]["users_id"], $_POST['firstname'], $_POST['lastname'], $_POST['email'], $session_user["0"]["users_usertypeid"], $password);
                    header('Location: ' . $_SERVER['PHP_SELF']);
            endif;
            endif;
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <h2><i class="fas fa-user-circle"></i> <?php echo $transLang['EDIT_PROFILE']; ?></h2>
                </div>
            </div>
            <p class="lead"><?php echo $transLang['ACCOUNT_INFO_DESC'] . $minpasslength; ?></p>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <fieldset>
                    <div class="form-group row">
                        <label for="username" class="col-sm-2 col-form-label"><?php echo $transLang['USERNAME']; ?></label>
                        <div class="col-sm-2"><input class="form-control" type="text" name="username" id="username" maxlength="50" value="<?php echo $session_user["0"]["users_username"]; ?>" readonly /></div>
                        <label for="created" class="col-sm-2 col-form-label"><?php echo $transLang['CREATED']; ?></label>
                        <div class="col-sm-2"><input class="form-control" type="text" name="created" id="created" value="<?php echo $session_user["0"]["users_created"]; ?>" readonly /></div>
                        <label for="usertype" class="col-sm-2 col-form-label"><?php echo $transLang['USERTYPE']; ?></label>
                        <div class="col-sm-2"><input class="form-control" type="text" name="usertype" id="usertype" maxlength="50" value="<?php echo $transLang[$session_user["0"]["users_usertype"]]; ?>" readonly /></div>
                    </div>
                    <div class="form-group row">
                        <label for="firstname" class="col-sm-2 col-form-label"><?php echo $transLang['FIRSTNAME']; ?></label>
                        <div class="col-sm-2"><input class="form-control" type="text" name="firstname" id="firstname" maxlength="50" value="<?php echo $session_user["0"]["users_firstname"]; ?>" /></div>
                        <label for="lastname" class="col-sm-2 col-form-label"><?php echo $transLang['LASTNAME']; ?></label>
                        <div class="col-sm-2"><input class="form-control" type="text" name="lastname" id="lastname" maxlength="50" value="<?php echo $session_user["0"]["users_lastname"]; ?>" /></div>
                        <label for="email" class="col-sm-2 col-form-label"><?php echo $transLang['EMAIL']; ?></label>
                        <div class="col-sm-2"><input class="form-control" type="text" name="email" id="email" maxlength="100" value="<?php echo $session_user["0"]["users_email"]; ?>" /></div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label"><?php echo $transLang['NEW'] . " " . $transLang['PASSWORD']; ?></label>
                        <div class="col-sm-2"><input class="form-control" type="password" name="password" id="password" /></div>
                        <label for="newpassword2" class="col-sm-2 col-form-label"><?php echo $transLang['CONFIRM'] . " " . $transLang['NEW'] . " " . $transLang['PASSWORD']; ?></label>
                        <div class="col-sm-2"><input class="form-control" type="password" name="newpassword2" id="newpassword2" /></div>
                        <div class="col-sm-4"><button type="submit" name="saveprofile" id="saveprofile" class="form-control btn btn-block btn-primary"><?php echo $transLang['SAVE']; ?></button></div>
                    </div>
                </fieldset>
            </form>
        </div>

<!-- CONTENT END -->
<?php }; require_once("inc/footer.inc.php");

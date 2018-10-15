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
    $SiteInfo = new \App\LobbySIO\Database\SiteInfo();
    $Users = new \App\LobbySIO\Database\Users();
    if(!isset($_COOKIE['app_disp_lang'])) { $app_disp_lang = $StaticFunctions->getDefaultLanguage(); } else { $app_disp_lang = $_COOKIE['app_disp_lang']; };
    $Translate = new \App\LobbySIO\Language\Translate($app_disp_lang);
    $transLang =  $Translate->userLanguage();                    // SETUP TRANSLATOR
    $app_current_pagename = $transLang['LOGIN'];                 // PAGE FUNCTION
    $app_current_pageicon = '<i class="fas fa-sign-in-alt"></i> ';// PAGE ICON
    require_once("inc/header.inc.php");                          // SHOW HEADER
    if ($StaticFunctions->getSessionStatus() == true) {          // CHECK STATUS
        header('Location: index.php');                           // ELSE HOME
    } else { ?>
<!-- CONTENT START -->

<?php
// hash password for comparison
require_once("src/Misc/PasswordHash.php");
$hasher = new PasswordHash(8, FALSE);
// compare if posted
if (!empty($_POST)):
    $user = $Users->loginUser($_POST['username']);
    if ($user && $user[0]["users_password"] == $hasher->CheckPassword($_POST['password'], $user[0]["users_password"])):
        session_regenerate_id();
        $_SESSION['user_id']   = $user[0]["users_id"];
        $_SESSION['loggedIn']  = TRUE;
        $_SESSION['signature'] = md5($user[0]["users_id"] . $_SERVER['HTTP_USER_AGENT']);
        $_SESSION['firstname'] = $user[0]["users_firstname"];
        $_SESSION['lastname']  = $user[0]["users_lastname"];
        session_write_close();
        header("Location: index.php");
    endif;
endif;
?>

    <div class="container">
      <div class="row">
        <div class="col-sm">
          <?php if(isset($_COOKIE['app_site'])) { $timezone = $SiteInfo->getSiteInfo($siteid)[0]["sites_timezone"]; } else {$timezone = "UTC";}; ?>
          <p><b><?php echo $transLang['SITE']; ?>:</b> <?php echo $SiteInfo->getSiteInfo($siteid)[0]["sites_name"]; ?>
          <br><b><?php echo $transLang['TIMEZONE']; ?>:</b> <?php echo $SiteInfo->getSiteInfo($siteid)[0]["sites_timezone"]; ?></p>
        </div>
        <div class="col-sm">
          <button type="button" class="btn btn-block btn-lg btn-success" data-toggle="modal" data-target="#sitetimeModal"><?php echo $transLang['CHANGE']; ?></button>
        </div>
      </div>
      <br />
      <hr />
      <br />
      <form class="form-signin" action="login.php" method="post">
        <div class="input-group input-group-lg">
          <input type="text" class="form-control" aria-describedby="button-addon2" id="username" name="username" placeholder="<?php echo $transLang['USERNAME']; ?>" required autofocus>
          <input type="password" class="form-control" aria-describedby="button-addon2" id="password" name="password" placeholder="<?php echo $transLang['PASSWORD']; ?>" required autofocus>
          <div class="input-group-append">
            <button class="btn btn-success btn-block" type="submit" id="button-addon2" name="login"><?php echo $transLang['LOGIN']; ?></button>
          </div>
        </div>
      </form>
    </div>

<!-- CONTENT END -->
<?php }; require_once("inc/footer.inc.php");

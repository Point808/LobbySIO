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
    $app_current_pagename = $transLang['USERS'];                 // PAGE FUNCTION
    $app_current_pageicon = '<i class="fas fa-list"></i> ';      // PAGE ICON
    require_once("inc/header.inc.php");                          // SHOW HEADER
    if ($StaticFunctions->getSessionStatus() == false) {         // CHECK STATUS
        echo $StaticFunctions->killSession();                    // ELSE DIE
    } else { ?>
<!-- CONTENT START -->

<?php if (isset($session_user)) { if($session_user["0"]["users_usertype"] !== "ADMIN") { header("Location: index.php"); ?><h2 class="content-subhead"><?php echo $transLang['NOT_AUTHORIZED']; ?></h2><?php }; }; ?>

<?php

// delete user only if submitted by button
if (!empty($_POST['deluser'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE' || ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['_METHOD'] == 'DELETE')) {
        $deleteid = (int) $_POST['deleteid'];
        echo $Users->deleteUser($deleteid);
        echo "user deleted!";
        header('Location: ' . $_SERVER['PHP_SELF']);
    }
}

// Set up pagination
$page_num = 1;
if(!empty($_GET['pnum'])):
    $page_num = filter_input(INPUT_GET, 'pnum', FILTER_VALIDATE_INT);
    if(false === $page_num):
        $page_num = 1;
    endif;
endif;
$offset = ($page_num - 1) * $StaticFunctions->getPageRows();
$row_count = count($Users->getUserInfo("%", "%", "%"));
$page_count = 0;
if (0 === $row_count): else: $page_count = (int)ceil($row_count / $StaticFunctions->getPageRows()); if($page_num > $page_count): $page_num = 1; endif; endif;
?>




        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <h2><?php echo $app_current_pageicon . $transLang['USERS']; ?></h2>
                </div>
                <div class="col-sm">
                    <button type="button" class="btn btn-block btn-lg btn-success" data-toggle="modal" data-target="#addUserModal"><?php echo $transLang['ADD_USER']; ?></button>
                </div>
            </div>
            <?php echo '<ul class="pagination pagination-sm"><li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">' . $transLang['PAGE'] . '</a></li>'; for ($i = 1; $i <= $page_count; $i++): echo '<li class="page-item'; if ($i === $page_num): echo ' active'; else: echo ' '; endif; echo '"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?pnum=' . $i . '">' . $i . '</a></li>'; endfor; echo '</ul>'; ?>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th><?php echo $transLang['NAME']; ?></th><th><?php echo $transLang['USERNAME']; ?></th><th><?php echo $transLang['EMAIL']; ?></th><th><?php echo $transLang['CREATED']; ?></th><th><?php echo $transLang['USERTYPE']; ?></th><th><?php echo $transLang['ACTIONS']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($Users->getUserInfo("%", $StaticFunctions->getPageRows(), $offset) as $row): ?>
                    <tr>
                        <td><?php echo $row['users_lastname'] . ", " . $row['users_firstname']; ?></td><td><?php echo $row['users_username']; ?></td><td><?php echo $row['users_email']; ?></td><td><?php echo $row['users_created']; ?></td><td><?php echo $transLang[$row['users_usertype']]; ?></td><td><form method="post" onsubmit="return confirm('<?php echo $transLang['DELETE_WARNING']; ?>')"><input type="hidden" id="_METHOD" name="_METHOD" value="DELETE" /><input type="hidden" id="deleteid" name="deleteid" value="<?php echo $row['users_id']; ?>" /><button class="button-error pure-button" id="deluser" name="deluser" value="deluser" type="submit" <?php if ($row['users_username'] == "admin"): echo "disabled"; endif; ?>><i class="fa fa-trash"></i> </button></form></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        $minpasslength = $StaticFunctions->getMinPass();
        ?>

        <!-- MODAL START -->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="Site" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="AddUser"><?php echo $transLang['ADD_USER']; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-inline my-2 my-lg-0" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="row">
                                <div class="col-sm">
                                    <p class="lead"><?php echo $transLang['ADD_USER_DESC'] . $minpasslength; ?></p>
                                </div>
                            </div>
                            <?php
                                // new user pw check
                                require_once("src/Misc/PasswordHash.php");
                                if (!empty($_POST['newuser'])):
                                if (empty($_POST['username'])): $errors['username'] = $transLang['USERNAME_NOTEMPTY']; endif;
                                if (preg_match('/[^a-zA-Z0-9 .-_]/', $_POST['username'])): $errors['username'] = $transLang['ILLEGAL_CHARACTERS']; endif;
                                if (empty($_POST['password'])): $errors['password'] = $transLang['PASSWORD_NOTEMPTY']; endif;
                                if (strlen($_POST['password']) < $minpasslength): $errors['password'] = $transLang['MIN_PASSWORD_LENGTH'] . $minpasslength; endif;
                                if (empty($_POST['password_confirm'])): $errors['password_confirm'] = $transLang['PASSWORD_NOTCONFIRMED']; endif;
                                if ($_POST['password'] != $_POST['password_confirm']): $errors['password_confirm'] = $transLang['PASSWORD_NOTMATCH']; endif;
                                $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                                if (!$email): $errors['email'] = $transLang['EMAIL_NOTVALID']; endif;
                                $existing = $Users->checkUser($_POST['username'], $email);
                                if ($existing):
                                    if ($existing[0]["users_username"] == $_POST['username']): $errors['username'] = $transLang['USERNAME_USED']; endif;
                                    if ($existing[0]["users_email"] == $email): $errors['email'] = $transLang['PASSWORD_USED']; endif;
                                endif;
                                endif;
                                if (!empty($_POST['newuser']) && empty($errors)):
                                    $hasher = new PasswordHash(8, FALSE);
                                    $password = $hasher->HashPassword($_POST['password']);
                                    $Users->addUser($_POST['firstname'], $_POST['lastname'], $_POST['username'], $timezone, $password, $_POST['email'], $_POST['usertype']);
                                    header('Location: ' . $_SERVER['PHP_SELF']);
                                endif;
                            ?>
                                <fieldset id="registration">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm">
                                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required />
                                            </div>
                                            <div class="col-sm">
                                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required />
                                            </div>
                                            <div class="col-sm">
                                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
                                            </div>
                                            <div class="col-sm">
                                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm Password" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm">
                                                <select class="custom-select" id="usertype" aria-label="<?php echo $transLang['ACCESS_LEVEL']; ?>" name="usertype" required>
                                                    <option value="" selected><?php echo $transLang['CHOOSE']; ?> <?php echo $transLang['ACCESS_LEVEL']; ?></option><?php foreach($Users->getUserTypeInfo("%") as $row): ?>
                                                    <option value="<?php echo $row['usertypes_id']; ?>"><?php echo $transLang[$row['usertypes_name']]; ?></option><?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-sm">
                                                <button type="submit" class="form-control btn btn-block btn-primary" value="Submit" name="newuser"><i class="fa fa-user-plus"></i> <?php echo $transLang['ADD_USER']; ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL END -->

<!-- CONTENT END -->
<?php }; require_once("inc/footer.inc.php");

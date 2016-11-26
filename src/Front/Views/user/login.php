<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 12:01
 */
?>
<section class="loginform cf">
    <form name="login" action="<?php echo $action ?>" method="post" accept-charset="utf-8">
        <span style="width:100%; background-color:#FF0000; color:#FFFFFF; "><?php if (isset($error)) {
                echo $error;
            } ?></span>
        <ul style="width:100%;">
            <li>
                <label for="usermail">Login</label>
                <input name="user[username]" placeholder="login" required
                       value="<?php if (isset($loginUser['username'])) {
                           echo $loginUser['username'];
                       } ?>">
            </li>
            <li>
                <label for="user[password]">Password</label>
                <input type="password" name="user[password]" placeholder="password" required></li>
            <li>
                <input type="submit" value="Login">
            </li>
        </ul>

        <ul style="width:100%; clear: both;">
            <li>
                <label>Civilité</label>
                Monsieur : <input type="checkbox" name="user[gender]" value="Monsieur">
                Madame : <input type="checkbox" name="user[gender]" value="Madame">
            </li>
        </ul>

        <ul style="width:100%; clear: both;">
            <li>
                <label>Nom</label>
                <input type="text" name="user[last_name]" value="" placeholder="nom">
            </li>
            <li>
                <label>Prénom</label>
                <input type="text" name="user[first_name]" value="" placeholder="prénom">
            </li>
        </ul>

        <ul style="width:100%; clear: both;">
            <li>
                <label>Age</label>
                <input type="text" name="user[age]" value="" placeholder="19">
            </li>
            <li>
                <label>Email</label>
                <input type="text" name="user[email]" value="" placeholder="yourname@domain.com">
            </li>
        </ul>
    </form>
</section>

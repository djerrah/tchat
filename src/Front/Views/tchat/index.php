<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 12:07
 */
?>

<?php if ($displayForm): ?>
    <h1>Tchat << <?php echo sprintf('%s', $user->username) ?> >></h1>
<ul id="tchat_content" style="width: 100%; height:300px; overflow:scroll; border: solid 1px #000000; ">
    <?php endif ?>

    <?php foreach ($tchats as $tchat): ?>
        <li>
            <span style="background-color: <?php if ($tchat->online): ?>#419641<?php else: ?>n#419643<?php endif ?>">
                <b><?php echo sprintf('%s', $tchat->username) ?></b>
            </span>
            :
            <?php echo html_entity_decode($tchat->body); ?>
        </li>
    <?php endforeach ?>


    <?php if ($displayForm): ?>

</ul>
    <form id="tchat_form" name="tchat" action="<?php echo $action ?>" method="post" accept-charset="utf-8">
        <input style="width: 100%;" id="tchat_form_body" name="tchat[body]" placeholder="your message hear" required value="">
    </form>
<?php endif ?>


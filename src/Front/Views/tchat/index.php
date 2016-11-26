<?php
/**
 * Created by PhpStorm.
 * User: djerrah
 * Date: 26/11/16
 * Time: 12:07
 */
?>

<h1>Tchat << TEST USER >></h1>


<ul style="width: 100%; height:300px; overflow:scroll; border: solid 1px #000000; ">
    messages
</ul>

<form name="tchat" action="<?php echo $action ?>" method="post" accept-charset="utf-8">
    <input name="tchat[body]" placeholder="your message hear" required value="">
</form>

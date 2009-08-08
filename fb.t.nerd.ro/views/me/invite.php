<?php if($invited) {
    echo "<div id=\"thankbox\" >";
        echo $invited;
    echo "</div>";
} else {
?>
<fb:fbml>
    <fb:request-form action="<?php echo "http://apps.facebook.com/".$app_url."/".$invite_href; ?>" method="POST" invite="true"
        type="<?php echo htmlentities($app_name,ENT_COMPAT,'UTF-8'); ?>" content="<?php echo htmlentities($content,ENT_COMPAT,'UTF-8'); ?>" >
        <fb:multi-friend-selector showborder="false" actiontext="Invite your friends to use <?php echo htmlentities($app_name,ENT_COMPAT,'UTF-8'); ?>.">
    </fb:request-form>
</fb:fbml>
<? }
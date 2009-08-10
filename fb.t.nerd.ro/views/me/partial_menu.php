<?php
    function submenu($uri, $message, $links) {
?>
    <div id="submenu">
        <h3><?php echo $message; ?></h3>
            <ul>
                    <?php
                    //For each link
                    foreach($links as $name => $link) {
                            //If this this link is the current one in the URI
                            if(stripos($uri, $link) !== FALSE) {
                                    print '<li class="selected">';
                            } else {
                                    print '<li>';
                            }
                            print '<a href="'. SITE_URL. $link. '">'. $name. '</a></li>';
                    }
                    ?>
            </ul>
    </div>
<?php } ?>
<?php
    // This generates our submenu
    include_once('partial_menu.php');
    $uri = $this->routes->fetch();
    $message = $this->views['message'];
    $links = array(
            'Add new idea' => 'me/new_idea',
            'List all' => 'me/all',
            'Invite your friends' => 'me/invite',
    );
    submenu($uri, $message, $links);
?>
<br />
<fb:success>
    <fb:message>
        Thank you for your contribution.
    </fb:message>
</fb:success>
<br />
<fb:explanation>
    <fb:message>Invite your friends!</fb:message>
    <?php
        echo "<fb:name uid=\"".$this->views['user_id']."\" useyou=\"false\" />";
    ?>
    , would you like to invite your friends to vote for your idea? <a href="/fdbussiness/me/invite">Just do it now</a>!
</fb:explanation>
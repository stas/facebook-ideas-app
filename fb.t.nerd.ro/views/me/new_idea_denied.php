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
<fb:error>
    <fb:message>We are sorry, but you are not allowed to participate with ideas.</fb:message>
        Due to your age
        (means you are older than minimum allowed participants age) 
        or your age is not available for us
        (this can be fixed by checking the privacy options or setting up the birthday in
        <?php
            echo "<fb:name uid=\"".$this->views['user_id']."\" possessive=\"true\" />";
        ?>
        profile).
</fb:error>
<br />
<fb:explanation> 
    <fb:message>Please don't leave us!</fb:message> 
        You are still allowed to participate in the following:
        <ul>
            <li>Browse participants ideas</li>
            <li>Comment on the participants ideas</li>
            <li>Rate participants ideas</li>
        </ul>
</fb:explanation>
<br />
<fb:success>
    <fb:message>Invite your friends!</fb:message>
    <?php
        echo "<fb:name uid=\"".$this->views['user_id']."\" useyou=\"false\" />";
    ?>
    , would you like to invite your friends to vote for your idea? <a href="/ideasapp/me/invite">Just do it now</a>!
</div>
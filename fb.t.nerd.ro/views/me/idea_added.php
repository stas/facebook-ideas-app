<fb:success>
    <fb:message>
        Thank you for your contribution.
    </fb:message>
</fb:success>
<fb:explanation>
    <fb:message>Invite your friends!</fb:message>
    <?php
        echo "<fb:name uid=\"".$this->views['user_id']."\" useyou=\"false\" />";
    ?>
    , would you like to invite your friends to vote for your idea? <a href="/fdbussiness/me/invite">Just do it now</a>!
</fb:explanation>
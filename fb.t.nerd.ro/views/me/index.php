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
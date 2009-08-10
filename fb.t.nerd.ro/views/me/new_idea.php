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
<?php
    if($failed) {
        print $this->validation->display_errors();
    }
?>
<fb:mediaheader uid="<?php echo $this->user_id; ?>">
    <fb:header-title><?php echo $this->views['message']; ?></fb:header-title>
</fb:mediaheader>
<fb:editor action="<?php echo SITE_URL; ?>me/add" labelwidth="150" width="600">
    <fb:editor-text label="Give a title to your idea" name="title" />
        <fb:editor-custom>Maximum 150 characters</fb:editor-custom>
    <fb:editor-textarea label="Describe your idea" name="text" rows="20" />
        <fb:editor-custom>Maximum 2000 characters</fb:editor-custom>
    <fb:editor-buttonset> 
        <fb:editor-button value="Add my idea" name="addnew" />
        <fb:editor-cancel value="Cancel" href="<?php echo SITE_URL; ?>me" />
    </fb:editor-buttonset> 
</form>
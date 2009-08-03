<?php
    if($failed) {
        print $this->validation->display_errors();
    }
?>

<fb:editor action="/fdbussiness/me/add" labelwidth="200" width="500"> 
    <fb:editor-text label="Give a title to your idea" name="title" />
    <fb:editor-textarea label="Describe your idea" name="text" rows="20" />
    <fb:editor-buttonset> 
        <fb:editor-button value="Add my idea" name="addnew" />
        <fb:editor-cancel value="Cancel" href="/fdbussiness/me" />
    </fb:editor-buttonset> 
</form>
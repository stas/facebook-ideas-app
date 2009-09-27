<!--div id="thenavigation">
    <?php echo $idea[0]->title; ?>
</div-->
<?php
    /*
     * Generates markup for ratings
     */
    function rating($r, $v, $id) {
        if($v == 0)
            $v = 1; //get rid of divisions by zero
        $r = $r / $v; //calculate rating
        switch($r) {
            case ($r < 1):
                $class = "nostar"; break;
            case ($r < 2):
                $class = "onestar"; break;
            case ($r < 3):
                $class = "twostar"; break;
            case ($r < 4):
                $class = "threestar"; break;
            case ($r < 5):
                $class = "fourstar"; break;
            case ($r < 6):
                $class = "fivestar"; break;
            default:
                $class = "nostar"; break;
        };
        
        $rating = '
            <ul class="rating '.$class.'">
            <li class="one"><a href="'.SITE_URL.'ideas/rate/'.$id.'/1" title="1 Star">1</a></li>
            <li class="two"><a href="'.SITE_URL.'ideas/rate/'.$id.'/2" title="2 Stars">2</a></li>
            <li class="three"><a href="'.SITE_URL.'ideas/rate/'.$id.'/3" title="3 Stars">3</a></li>
            <li class="four"><a href="'.SITE_URL.'ideas/rate/'.$id.'/4" title="4 Stars">4</a></li>
            <li class="five"><a href="'.SITE_URL.'ideas/rate/'.$id.'/5" title="5 Stars">5</a></li>
            </ul>
        ';
        
        return $rating;
    }
    
?>

<fb:mediaheader uid="<?php echo $idea[0]->aid ?>">
    <fb:header-title><?php echo $idea[0]->title; ?></fb:header-title>
    <fb:owner-action href="/<?php echo $this->app_url; ?>/ideas/delete/<?php echo $idea[0]->id ?>">Delete</fb:owner-action>
</fb:mediaheader>
<div id="idea_text">
    <p><?php echo $idea[0]->message; ?></p>
</div>
<div id="idea_rating">
    <?php echo rating($idea[0]->rating, $idea[0]->votes, $idea[0]->id); ?>
    <?php
        if(isset($rated) && $rated) :
    ?>
        <span style="border-bottom: 1px solid #e2c822; padding: 1px;">Thank you for your vote!</span>
    <?php
        endif;
    ?>
    Current rating <?php if($idea[0]->votes) echo "is ".round($idea[0]->rating / $idea[0]->votes, 2); ?> based on <?php echo $idea[0]->votes; ?> vote(s):
</div>
<div id="idea_comments">
    <fb:comments xid="<?php echo $this->app_url . '_'. $idea[0]->id; ?>" canpost="true" candelete="false" returnurl="http://apps.facebook.com/<?php echo $this->app_url.'/'.$this->routes->uri_string ?>" callbackurl="http://apps.facebook.com/<?php echo $this->app_url.'/'.$this->routes->uri_string ?>" showform="true">
    </fb:comments>
</div>
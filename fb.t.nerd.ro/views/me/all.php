<div id="thenavigation" >
    My ideas
</div>
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
<div>
    <table id="mine_ideas">
            <tr>
                <th class="first">Title for the idea</th>
                <th>Idea's rating</th>
            </tr>
            <?php
                $odd = 0;
                foreach($ideas as $idea) {
                    if($odd % 2 == 0)
                        echo "<tr class=\"odd\">";
                    else
                        echo "<tr>";
                    echo '<td><h4><a href="/'.$this->app_url .'/ideas/see/'. $idea->id .'">'. $idea->title.'</a></h4></td>';
                    echo "<td>".rating($idea->rating, $idea->votes, $idea->id)."</td>";
                    echo "</tr>";
                    $odd++;
                }
            ?>
            <?php if(isset($hasposts)) : // has pagination ?>
            <tr>
                <th class="bottom">
                <?php
                    if($hasposts < 0) : // at the end
                ?>
                <a href="<?php echo SITE_URL; ?>me/all/<?php echo $page - 1; ?>">&larr; Go back</a> | Page <?php echo $page; ?></a>
                <?php
                    elseif($hasposts == 0) : //during pagination
                ?>
                <a href="<?php echo SITE_URL; ?>me/all/<?php echo $page - 1; ?>">&larr; Go back</a> | Page <?php echo $page; ?> | <a href="<?php echo SITE_URL; ?>me/all/<?php echo $page + 1; ?>">Next page &rarr</a>
                   <?php
                    elseif($hasposts > 0) : //on start
                ?>
                Page <?php echo $page; ?> | <a href="<?php echo SITE_URL; ?>me/all/<?php echo $page + 1; ?>">Next page &rarr</a>
                </th>
                <?php endif; ?>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <?php endif; ?>
    </table>
</div>
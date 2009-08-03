<div id="submenu">
        <ul>
                <?php
                //Get the URI of this page
                $uri = $this->routes->fetch();

                //Create a list of the menu links
                $links = array(
                        'Add new idea' => 'me/new_idea',
                        'List all' => 'me/'
                );

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
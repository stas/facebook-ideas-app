<fb:if-is-app-user>
	<link rel="stylesheet" href="http://fb.t.nerd.ro/fb.t.nerd.ro/views/style.css?<?php echo rand(); ?>" type="text/css" media="screen" />
	<?php if(!empty($header_files)) { print $header_files; } ?>
	<fb:dashboard>
		<fb:create-button href="http://apps.facebook.com/fdbussiness/me/new_idea">Add a new idea!</fb:create-button>
        </fb:dashboard>
	<?php
	    //echo "<p>Hello, <fb:name uid=\"$user_id\" useyou=\"false\" />!</p>";
	    //echo "<h2>$message</h2>";
	?>
	<div id="container">
		<div id="menu">
			<fb:tabs>
				<?php
				//Get the URI of this page
				$uri = $this->routes->fetch();
			
				//Create a list of the menu links
				$links = array(
					'Homepage' => 'home',
					'My Ideas' => 'me',
					'Friends Ideas' => 'friends',
					'Top Ideas' => 'ideas/top_ideas',
					'New Ideas' => 'ideas/new_ideas',
					'All' => 'ideas/all',
				);
	
				//For each link
				foreach($links as $name => $link) {
					//If this this link is the current one in the URI
					if(stripos($uri, $link) !== FALSE) {
						print "<fb:tab-item href=\"". SITE_URL. $link. "\" title=\"". $name."\" selected=\"true\" />";
					} else {
						print "<fb:tab-item href=\"". SITE_URL. $link. "\" title=\"". $name."\" />";
					}
				}
				?>
			</fb:tabs>
		</div>
	
		<?php
		/*
			echo "<fb:mediaheader uid=\"$user_id\">";
				echo "<fb:header-title>";
					echo "$message";
				echo "</fb:header-title>";
			echo "</fb:mediaheader>";
		*/
			print "
				<div id=\"main\">
					<div class=\"wrapper\">
						$content
					</div>
				</div>
				";
		?>
		
		<div id="footer">
			<div class="wrapper">
				<!--p>Page rendered in <?php print round((microtime(true) - START_TIME), 5); ?> seconds
				taking <?php print round((memory_get_usage() - START_MEMORY_USAGE) / 1024, 2); ?> KB
				(<?php print (memory_get_usage() - START_MEMORY_USAGE); ?> Bytes).</p-->
			</div>
		</div>
	</div>
<fb:else>
	<fb:redirect url="http://www.facebook.com/login.php?v=1.0&api_key=[your_app_key]&next=[your_canvas_page_URL]&canvas="/>
</fb:else>
</fb:if-is-app-user>
<link rel="stylesheet" href="<?php print THEME_URL; ?>style.css" type="text/css" media="screen" />
<?php if(!empty($header_files)) { print $header_files; } ?>
<div id="container">
	<div id="menu">
		<ul>
			<?php
			//Get the URI of this page
			$uri = $this->routes->fetch();

			//Create a list of the menu links
			$links = array(
				'My Ideas' => 'me',
				'Friends Ideas' => 'friends',
				'Top Ideas' => 'top',
				'New Ideas' => 'new',
				'All' => 'all',
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

	<div id="main">
		<div class="wrapper">
			<?php print $content; ?>
		</div>
	</div>

	<div id="footer">
		<div class="wrapper">
			<!--p>Page rendered in <?php print round((microtime(true) - START_TIME), 5); ?> seconds
			taking <?php print round((memory_get_usage() - START_MEMORY_USAGE) / 1024, 2); ?> KB
			(<?php print (memory_get_usage() - START_MEMORY_USAGE); ?> Bytes).</p-->
		</div>
	</div>
</div>
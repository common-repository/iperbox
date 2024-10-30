<?php
/*
Plugin Name: IperBox
Plugin URI: http://www.heiste.de/iperbox/
Description: Show Pictures from Ipernity on Sidebar. 
Author: Heino Stegen
Version: 1.07
Author URI: http://www.heiste.de/
*/
/*

Changes
2010-01-11 added cURL extention (thanks to Marcel )
2009-12-02 added prev - next when opened in lightbox
2009-10-20 choice of lightbox picture sizes, medium (560px) or large (1024px)
2009-05-17 added thumbs from ipernity to maintain aspect ratio
2009-04-05 validated xhtml and css markup according wc3.org 
2009-04-04 added group_id to show pics of groups
2009-04-04 clean up html 
2009-03-22 complete rewritten using new feed-url
2009-03-15 User can use slideshow as on Ipernity page
2009-03-15 User can decide if lightbox plugin is used
2009-03-15 Link goes to appropiate Ipernity page if lightbox not chosen
*/

 //error_reporting(E_ERROR);


 function iperbox($userid, $anzahl, $group, $album, $box, $slideshow, $size, $sizel) {
 
if ($group) {  
$rss = "http://www.ipernity.com/feed/doc?group_id=".$group."&only=photo";
} 
  
else if ($album) {  
$rss = "http://www.ipernity.com/feed/doc?album_id=".$album."&only=photo";
}
else {
	$rss = "http://www.ipernity.com/feed/doc?user_id=".$userid."&only=photo";
	}
	
	if (function_exists('curl_init')) {
   // initialize a new curl resource
   $ch = curl_init();
   // set the url to fetch
  curl_setopt($ch, CURLOPT_URL, $rss);
   // don't give me the headers just the content
  curl_setopt($ch, CURLOPT_HEADER, 0);
   // return the value instead of printing the response to browser
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 $content = curl_exec($ch);
   // remember to always close the session and free all resources
   curl_close($ch);

} else if(function_exists('file_get_contents')){
	$content = file_get_contents($rss);
	 }
		
		$sx = simplexml_load_string($content);
		$count = $anzahl;
		$i=0;
		foreach ($sx->entry as $picture)  
  {
	$title = $picture->title;  
	$attr = $picture->link->attributes();
	$orig = $picture->link[1]->attributes();
	$lightpic = $picture->link[2]->attributes();
	$pic = $picture->link[3]->attributes();
	
	   //getting the size of lightbox
	$subject1 = $lightpic['href'];
        $pattern1 = '#240.jpg#';
        $replace1 = '560.jpg';
         $result1 = preg_replace($pattern1, $replace1, $subject1);
  
	//setting size of lightbox
	 if ($sizel == '1024') {
		$linkl = $orig['href'];
		}
	else if ($sizel == '560') {
		$linkl = $result1;
		}
	
	//getting the thumbs
	$subject = $pic['href'];
    $pattern = '#100.jpg#';
    $replace = '75x.jpg';
    $result = preg_replace($pattern, $replace, $subject);
  
	//setting size of thumbs
	 if ($size == '100') {
		$link = $pic['href'];
		}
	else if ($size == '75') {
		$link = $result;
		}
	
     
      //just display slideshow
		if ($slideshow == '1') {
		echo '<object type="application/x-shockwave-flash"
data="http://www.ipernity.com/widgets/minislideshow.swf?user='.$userid.'" 
width="200" height="200">
<param name="movie" 
value="http://www.ipernity.com/widgets/minislideshow.swf?user='.$userid.'" />
</object>';
		return;
		}
		//display when using lightbox
	else if ($box == '1') {
		echo '<a href="'.$linkl.'" rel="lightbox[iperbox]" title="&lt;a href=&quot;'.$attr['href'].'&quot; target=_blank&gt;'.$title.'&lt;/a&gt; || View picture at Ipernity.com" ><img src="'.$link.'" alt="'.$title.'" width="'.$size.'" height="'.$size.'"  class="iperbox"/></a>';
	$i++;
      if ($i == $count) { return; }	
  } 	
  //display normal mode
	else {
 echo '<a href="'.$attr['href'].'"><img src="'.$link.'" alt="'.$title.'" class="iperbox"/></a>';
$i++;
      if ($i == $count) { return; }	
  } 	
	}
  }
 

function widget_iperbox($args) {
  extract($args);

  $options = get_option("widget_iperbox");
  if ( !is_array( $options ) )
	$options = array('title' => 'Recently on Ipernity', 'user' => '', 'group' =>'', 'album' => '', 'anzahl' => '4', 'box'=> '','slideshow'=> '', 'size'=> '', 'sizel'=> '');
  

  echo $before_widget;
    echo $before_title;
      echo $options['title'];
    echo $after_title;
    //Our Widget Content	
    iperbox($options['user'], $options['anzahl'], $options['group'], $options['album'], $options['box'], $options['slideshow'], $options['size'], $options['sizel']);
	
  echo $after_widget;
}

function iperbox_control()
{
  $options = get_option("widget_iperbox");

 $options = get_option("widget_iperbox");
  if ( !is_array( $options ) )
	$options = array('title' => 'Recently on Ipernity', 'user' => '', 'group' =>'', 'album' => '', 'anzahl' => '4', 'box'=> '', 'slideshow' => '', 'size'=> '', 'sizel'=> '');
  

  if ($_POST['iperbox-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['iperbox-WidgetTitle']);
	$options['user'] = htmlspecialchars($_POST['iperbox-WidgetUser']);
	$options['anzahl'] = htmlspecialchars($_POST['iperbox-WidgetAnzahl']);
	$options['group'] = htmlspecialchars($_POST['iperbox-WidgetGroup']);
	$options['album'] = htmlspecialchars($_POST['iperbox-WidgetAlbum']);
	$options['box'] = htmlspecialchars($_POST['iperbox-WidgetBox']);
	$options['slideshow'] = htmlspecialchars($_POST['iperbox-WidgetSlideshow']);
	$options['size'] = htmlspecialchars($_POST['iperbox-WidgetSize']);
        $options['sizel'] = htmlspecialchars($_POST['iperbox-WidgetSizel']);
    update_option("widget_iperbox", $options);
  }

?>
<div>
  <p>
    <label for="iperbox-WidgetTitle" style="line-height:20px;display:block;"><?PHP _e('Title:' ); ?></label>
    <input style="width: 200px;" type="text" id="iperbox-WidgetTitle" name="iperbox-WidgetTitle" value="<?php echo $options['title'];?>" />
	</p>
	<p>
	<label for="iperbox-WidgetUser" style="line-height:20px;display:block;"><?PHP _e('Username:' ); ?></label>
    <input style="width: 200px;" type="text" id="iperbox-WidgetUser" name="iperbox-WidgetUser" value="<?php echo $options['user'];?>" />
	</p>
	<p>
	<label for="iperbox-WidgetSlideshow" style="line-height:20px;display:block;">
<input id="iperbox-WidgetSlideshow" name="iperbox-WidgetSlideshow" type="checkbox" value="1"<?PHP if ( 1 == $options['slideshow'] ) echo ' checked="checked"'; ?> />
			<?PHP _e('Show only Slideshow' ); ?>
		</label>
	</p>
	<p>
	<label for="iperbox-WidgetAnzahl" style="line-height:20px;display:block;"><?PHP _e('Count of pictures:' ); ?></label>
    <input style="width: 200px;" type="text" id="iperbox-WidgetAnzahl" name="iperbox-WidgetAnzahl" value="<?php echo $options['anzahl'];?>" />
	</p>
	<p>
		<label for="iperbox-WidgetSize"><?php _e('Thumbnail Size:', 'widgets'); ?> <br/><select style="width: 200px;" id="iperbox-WidgetSize" name="iperbox-WidgetSize"><option value="75" <?=($options['size']==75?'selected':'') ?> >75</option><option value="100" <?=($options['size']==100?'selected':'') ?> >100</option></select></label></p>
	<p>
	<?PHP _e('Show Pictures from a Group' ); ?> 
	<label for="iperbox-WidgetGroup" style="line-height:20px;display:block;"><small><?PHP _e('Name or Number of group:' ); ?></small></label>
    <input style="width: 200px;" type="text" id="iperbox-WidgetGroup" name="iperbox-WidgetGroup" value="<?php echo $options['group'];?>" />
	</p>
	<p>
	<?PHP _e('Show Pictures from an Album' ); ?> 
	<label for="iperbox-WidgetAlbum" style="line-height:20px;display:block;"><small><?PHP _e('Number of Album:' ); ?></small>: </label>
    <input style="width: 200px;" type="text" id="iperbox-WidgetAlbum" name="iperbox-WidgetAlbum" value="<?php echo $options['album'];?>" />
	</p>
	<p>
	<label for="iperbox-WidgetBox" style="line-height:20px;display:block;">
<input id="iperbox-WidgetBox" name="iperbox-WidgetBox" type="checkbox" value="1"<?PHP if ( 1 == $options['box'] ) echo ' checked="checked"'; ?> />
			<?PHP _e('Use Lightbox' ); ?>
		</label>
		</p>
<p>
		<label for="iperbox-WidgetSizel"><?php _e('Image Size in Lightbox:', 'widgets'); ?> <br/><select style="width: 200px;" id="iperbox-WidgetSizel" name="iperbox-WidgetSizel"><option value="560" <?=($options['sizel']==560?'selected':'') ?> >560 medium</option><option value="1024" <?=($options['sizel']==1024?'selected':'') ?> >1024 large</option></select></label></p>

		

	<p>
	<small><?PHP _e('for more help:' ); ?> <a href="http://www.heiste.de/iperbox/">www.heiste.de</a></small> 
	</p>
    <input type="hidden" id="iperbox-Submit" name="iperbox-Submit" value="1" />
	</div>
<?php
}

function iperbox_init()
{
  register_sidebar_widget(__('IperBox'), 'widget_iperbox');
  register_widget_control(   'IperBox', 'iperbox_control', 200, 100 );
}
add_action('plugins_loaded', 'iperbox_init');

// Option wieder aus der Datenbank entfernen wenn das Plugin deaktiviert wird!
function iperbox_deaktivieren() {
  // Option aus der Datenbank entfernen
  delete_option( 'widget_iperbox' );
}
add_action( 'deactivate_'.plugin_basename(__FILE__), 'iperbox_deaktivieren' );
?>

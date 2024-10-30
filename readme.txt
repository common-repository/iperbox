=== Iperbox === 
Contributors: Heino Stegen
Tags: photos, ipernity, widget, images
Requires at least: 2.7
Tested up to: 2.9.1
Stable tag: trunk

Show your Ipernity Pictures on Wordpress Sidebar


== Description == 

Show your Ipernity Pictures on Wordpress Sidebar. You can switch between showing your latest photos or the slideshow as on the Ipernity page. Very simple to install and use. 


== Changelog ==
-------
= Version 1.07 =
* Added cURL extention to fetch the feed (thanks to Marcel)
= Version 1.06 =
* Added prev - next when opened in lightbox
= Version 1.05 =
* Added a choice for size of picture to be viewed in Lightbox, medium (560px) or large (1024px)
= Version 1.04 =
* There is now a choice of size for thumbnails, added thumbnails from ipernity to maintain aspect ratio of preview. 
= Version 1.03 =
* validate xhtml and css markup according to http://validator.w3.org/
= Version 1.02 =
* added Group_id to shows pictures from group
= Version 1.01 =
* Complete rewrite. The new feed address of Ipernity is now used
= Version 0.9  =
* you have the option to show the slideshow as on Ipernity page.
* if there is no Lightbox Plugin (such as wp-lightbox2 or slimbox) installed, the link leads direct to the photo page on Ipernity.


== Installation ==
---------------
1. Uncompress the download package
1. Upload the folder iperbox to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add Iperbox as a widget to sidebar at the 'widget' menu. 
1. Fill in the settings according your needs. Important is your Ipernity username.The rest can be left as is. 
Save the changes und enjoy your pictures on the sidebar.


== Frequently Asked Questions ==

= My theme doesn't support Widgets? =

If you dont have a widget ready theme you can still enjoy this plugin.
Just install the plugin as above described and activate it. Then edit your sidebar.php and add following code.
< ?php iperbox('USERNAME', 4, 'GROUPNAME', ALBUMNUMBER, 0, 0, SIZE, SIZEL) ? >
Which means:
USERNAME (your Ipernity username) 
4 (count of pictures)
GROUPNAME (leave '' for no group)
ALBUMNUMBER (Number of Album 0=show latest pics or 5 digit album number i.e. 72535
0 (use lightbox = 1 for yes, 0 for no)
0 (show only Slideshow = 1 for yes, 0 for no)
SIZE (75 for thumbs, 100 for normal preview)
SIZEL (560 for medium, 1024 for large picture in lightbox)

= How can I change the look of the widget? =

If you want to adjust the look of the widget. put something n your stylesheet like: 
.iperbox { padding: 2px; margin: 2px; border: solid 1px; color: gray } 
i 

= Help I get an error =
After activating the plugin all I get is an error message:
fatal error: Call to undefined function: simplexml_load_string() in /wp-content/plugins/iperbox/iperbox.php on line 40

* The plugin needs php 5.0 or higher.If you still have php4 running, you will get this error and the plugin does not work. I'm sorry but I haven't found a solution for this yet as the function I use is not implemented in php 4. Please upgrade to php 5.x



== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png

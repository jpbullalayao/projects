<?php
/*
* 
* @package      Joomla-MyBB integration
* @version		1.0 November 2010
* @author		Matteo Vignoli
* @copyright	Copyright (C) 2010 - Vigmacode
* @license		GNU/GPL
* @website		http://www.vigmacode.net
* @email		webmaster@vigmacode.net
* 
*/


// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<table class="<?php echo $modClass; ?>">

<?php

foreach($result_list as $result_row) {
 ?>
 <tr><td>
 <?php
  if ($image_path){
  ?>
    <img src="<?php echo $image_path;?>" id="latestImage" />
  <?php	
  }
  ?>
 </td>
 <td width="<?php echo $tab_width; ?>" height="<?php echo $tab_height; ?>">
 <?php
 if($showLink == '1') {
 ?>
 <a href="<?php echo $forum_url; ?>showthread.php?tid=<?php echo $result_row['tid']; ?>&amp;action=lastpost" target="_blank"><?php echo $result_row['subject'];?></a>
 <?php
 } else {
 ?>
 <?php echo $result_row['subject']; ?>
 
 <?php
 }
 ?>
 </td></tr>
<?php	
}

?>
</table>
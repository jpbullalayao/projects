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
// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

//class
$modClass	 	  	 = $params->get('moduleStyle');

//activators
$showLink			 = $params->get('showLink'); // 0,1
$showType			 = $params->get('showType'); 

//layout
$rowWidth			 = $params->get('rowHeight');
$rowHeight			 = $params->get('rowWidth');
$imagePath			 = $params->get('imagePath');

//other
$displayLimit		 = $params->get('threadsToShow');
$forumUrl		     = $params->get('forumUrl');
$forbiddenThreads	 = $params->get('forbiddenThreads');
$mybb_table			 = $params->get('mybbTable');


if($mybb_table == '') {
  $mybb_table = 'mybb_';
}

$image_path = modMybbLatestThreadsHelper::getImagePath($imagePath);

$forum_url = modMybbLatestThreadsHelper::validateUrl($forumUrl, $this->baseurl);

$array_protected = modMybbLatestThreadsHelper::getProtectedThreads($forbiddenThreads);

$result_list = modMybbLatestThreadsHelper::getLatestThreads($mybb_table, $array_protected, $showType, $displayLimit);

$row_width = modMybbLatestThreadsHelper::getDimensions($tabWidth);
$row_height = modMybbLatestThreadsHelper::getDimensions($tabHeight);

require(JModuleHelper::getLayoutPath('mod_mybblatestthreads'));
?>

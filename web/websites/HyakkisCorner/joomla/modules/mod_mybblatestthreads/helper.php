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


class modMybbLatestThreadsHelper
{

  function getImagePath($path)
  {
   
    $extension = explode(".", $path);
	$extension = strtolower($extension[count($extension)-1]);
	
	$accepted_extensions = array('jpg','jpeg','png','gif','bmp');
	
	//if file is not supported, filename is emptied
	if(in_array($extension, $accepted_extensions))
	{
		$path = $path;
	}
	else
	{
		$path = '';
	}
	
	//if Path is empty, either because left out or because invalid, the image won't be shown
  	if ($path == '')
	{
	 $path_to_image = NULL;
	}
	else
	{
	  $path_to_image = 'images/stories/'.$path;
	}
	
	return $path_to_image;
  }
  
  function getProtectedThreads($string)
  {
	if ($string == '')
	{
		$array = NULL;
	} else
	{
		$string = str_replace(' ','',$string);
		$array = explode(',',$string);
	}
	
	return $array;
  }
  
  
  
   function getLatestThreads($mybb, $arrayProtected, $showType, $limit)
   {
  	
	$db = &JFactory::getDBO();
	
	$mybb = $db->getEscaped($mybb);
	
	if($arrayProtected)
	{
	
	  $sanitized = array();
	  foreach($arrayProtected as $forbiddenId)
	  {
	  	$sanitized[] = intval($forbiddenId);
	  }
	  
	 $sanitized_string = implode(' AND != ',$sanitized);
	 
	 
	 $query = 'SELECT tid,fid,subject 
	           FROM '.$mybb.'threads 
			   WHERE fid != '.$sanitized_string.'
			   ORDER BY '.$showType.' desc LIMIT 0,'.$limit.'';
	 
	 } 
	 else
	 {
	 
	  
	  $query = 'SELECT tid,fid,subject
	            FROM '.$mybb.'threads
	  		    ORDER BY '.$showType.' DESC LIMIT 0,'.$limit.'';
	 }
	
	$db->setQuery($query);
	$multiarray = $db->loadAssocList();
	
	return $multiarray;
  }
  
	
   function validateUrl ($url, $baseurl)
   {
   
    if($url == '') {
    $url = $baseurl."/forum/";
    } else {

     $url = htmlspecialchars($url);
     if (preg_match("/^(https?:\/\/+[\w\-]+\.[\w\-]+)/i",$url))
	 {
     $url = $url;
     } else {
     $url = '';
     }	
    }
     return $url;
   
   }

   function getDimensions($dimension)
   { 
	  $layout_dimension = intval($dimension);
	  return $layout_dimension;
   }

}

?>
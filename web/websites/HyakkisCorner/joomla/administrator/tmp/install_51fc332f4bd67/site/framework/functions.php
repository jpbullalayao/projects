<?php
/**
 * @version		$Id: functions.php 01 2012-04-01 11:37:09Z maverick $
 * @package		CoreJoomla.Framework
 * @subpackage	Components.cjlib
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CJFunctions {

	private static $_country_names = array();
	
	/**
	 * Generates the pagination, given the non-Sef Joomla url (with itemid), start page number, current page number, total number of pages and number of rows per page.
	 *
	 * @param string $nonSefUrl
	 * @param int $start
	 * @param int $current
	 * @param int $total
	 * @param int $count
	 */
	public static function get_pagination($nonSefUrl, $start, $current, $total, $count=20, $boostrap = false){

		$content = '';
		
		if($boostrap){

			$content = $content.'<div class="cleafix">';
			$content = $content.'<div class="pull-right">'.sprintf(JText::_('COM_CJLIB_PAGINATION_PAGES_COUNT'), $current, $total).'</div>';
			$content = $content.'<div class="pagination pagination-small hidden-phone"><ul>';
			
			if( $total > 0 ){
				
				if($current == 1){
					
					$content = $content.'
							<li class="disabled">
								<a href="#" onclick="return false;" title="'.JText::_('COM_CJLIB_PAGINATION_START').'" class="tooltip-hover"><i class="icon-step-backward"></i></a>
							</li>';
					$content = $content.'
							<li class="disabled">
								<a href="#" onclick="return false;" title="'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'" class="tooltip-hover"><i class="icon-backward"></i></a>
							</li>';
				} else {
					
					$content = $content.'
							<li>
								<a href="'.JRoute::_( $nonSefUrl.'&start=0' ).'" 
									title="'.JText::_('COM_CJLIB_PAGINATION_START').'" class="tooltip-hover"><i class="icon-step-backward"></i></a>
							</li>';
					$content = $content.'
							<li>
								<a href="'.JRoute::_( $nonSefUrl.'&start='.( ($current - 2)*$count )).'" 
									title="'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'" class="tooltip-hover"><i class="icon-backward"></i></a>
							</li>';
				}
				
				for($i = $start; $i <= $total && $i <= $start + 9; $i++){
					
					$url = JRoute::_( $nonSefUrl.'&start='.( ( $i - 1 ) * $count ) );
					
					if($i == $current){
						
						$content = $content.'<li class="active"><a class="current" href="'.$url.'">'.$i.'</a></li>';
					} else {
						
						$content = $content.'<li><a href="'.$url.'">'.$i.'</a></li>';
					}
				}
				
				if($current == $total){
					
					$content = $content.'
							<li class="disabled">
								<a href="#" title="'.JText::_('COM_CJLIB_PAGINATION_NEXT').'" onclick="return false;" class="tooltip-hover"><i class="icon-forward"></i></a>
							</li>';
					$content = $content.'
							<li class="disabled">
								<a href="#" title="'.JText::_('COM_CJLIB_PAGINATION_LAST').'" onclick="return false;" class="tooltip-hover"><i class="icon-step-forward"></i></a>
							</li>';
				}else{
					
					$content = $content.'
							<li>
								<a href="'.JRoute::_( $nonSefUrl.'&start='.($current * $count) ).'" 
									title="'.JText::_('COM_CJLIB_PAGINATION_NEXT').'" class="tooltip-hover"><i class="icon-forward"></i></a>
							</li>';
					$content = $content.'
							<li>
								<a href="'.JRoute::_( $nonSefUrl.'&start='.(($total - 1) * $count) ).'" 
									title="'.JText::_('COM_CJLIB_PAGINATION_LAST').'" class="tooltip-hover"><i class="icon-step-forward"></i></a>
							</li>';
				}
			}
			
			$content = $content.'</ul></div>';
			
			$content = $content.'<div class="pagination visible-phone">';
			$content = $content.'<ul class="paginationpager">';
			if( $total > 0 ){
				
				if($current == 1){
					
					$content = $content.'
							<li class="disabled">
								<a href="#" onclick="return false;" 
									title="'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'" class="tooltip-hover">'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'</i></a>
							</li>';
				} else {
					
					$content = $content.'
							<li>
								<a href="'.JRoute::_( $nonSefUrl.'&start='.( ($current - 2)*$count )).'" 
									title="'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'" class="tooltip-hover">'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'</a>
							</li>';
				}
				
				if($current == $total){
					
					$content = $content.'
							<li class="disabled">
								<a href="#" title="'.JText::_('COM_CJLIB_PAGINATION_NEXT').'" onclick="return false;" class="tooltip-hover">'.JText::_('COM_CJLIB_PAGINATION_NEXT').'</a>
							</li>';
				}else{
					
					$content = $content.'
							<li>
								<a href="'.JRoute::_( $nonSefUrl.'&start='.($current * $count) ).'" 
									title="'.JText::_('COM_CJLIB_PAGINATION_NEXT').'" class="tooltip-hover">'.JText::_('COM_CJLIB_PAGINATION_NEXT').'</a>
							</li>';
				}
			}
			$content = $content.'</ul></div></div>';
		} else {
			
			$content = $content.'<div class="cjpagination">';
			$content = $content.'<div class="float-right">'.sprintf(JText::_('COM_CJLIB_PAGINATION_PAGES_COUNT'), $current, $total).'</div>';
	
			if( $total > 0 ){
	
				$first_disabled = ( $current == 1 ) ? ' disabled' : '';
				$last_disabled = ( $current == $total ) ? ' disabled' : ''; 
				
				$content = $content.'<div class="page-main">';
				$content = $content.'<a class="first'.$first_disabled.'" href="'.JRoute::_( $nonSefUrl.'&start=0' ).'" 
										title="'.JText::_('COM_CJLIB_PAGINATION_START').'">'.JText::_('COM_CJLIB_PAGINATION_START').'</a>';
				$content = $content.'<a class="previous'.$first_disabled.'" href="'.JRoute::_( $nonSefUrl.'&start='.( $current > 1 ? ( $current - 2 ) * $count : 0 ) ).'" 
										title="'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'">'.JText::_('COM_CJLIB_PAGINATION_PREVIOUS').'</a>';
	
				for($i = $start; $i <= $total && $i < $start + 10; $i++){
	
					$url = JRoute::_( $nonSefUrl.'&start='.( ( $i - 1 ) * $count ) );
	
					if($i == $current){
	
						$content = $content.'<a class="current" href="'.$url.'">'.$i.'</a>';
					} else{
	
						$content = $content.'<a href="'.$url.'">'.$i.'</a>';
					}
				}
	
				$content = $content.'<a class="next'.$last_disabled.'" href="'.JRoute::_( $nonSefUrl.'&start='.( $current < $total ? $current * $count : 0 ) ).'"
										title="'.JText::_('COM_CJLIB_PAGINATION_NEXT').'">'.JText::_('COM_CJLIB_PAGINATION_NEXT').'</a>';
				$content = $content.'<a class="last'.$last_disabled.'" href="'.JRoute::_( $nonSefUrl.'&start='.( ( $total - 1 ) * $count ) ).'" 
										title="'.JText::_('COM_CJLIB_PAGINATION_LAST').'">'.JText::_('COM_CJLIB_PAGINATION_LAST').'</a>';
				$content = $content.'</div>';
			}
	
			$content = $content.'<div class="clear"></div>';
			$content = $content.'</div>';
		}

		return $content;
	}
	
	/**
	 * Loads the jquery library and set of jquery plugins passed as parameters to the function.
	 *
	 * The required jquery plugins should be passed as associative array of names with name as libs
	 * Ex:
	 * <code>
	 * $params = array('libs'=>array('ui', 'form', 'validate', 'treeview', 'menu', 'waypoints', 'tags', 'inlinelabel', 'scrollto'), 'theme'=>'start');
	 * CJFunctions::load_jquery($params);
	 * </code>
	 *
	 * @param array $params
	 */
	public static function load_jquery($params=array()){

		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		
		$plugins = !empty($app->jqueryplugins) ? $app->jqueryplugins : array();
		$custom_tag = isset($params['custom_tag']) ? true : false;

		if(!in_array('baseloc', $plugins)){
				
			$document->addScriptDeclaration('var cjlib_loc = "'.CJLIB_URI.'";');
			$plugins[] = 'baseloc';
		}
		
		if(APP_VERSION <= 2.5 && !$app->get('jquery', false)){

			CJFunctions::add_script_to_document($document, 'jquery.min.js', $custom_tag);
			CJFunctions::add_script_to_document($document, 'jquery.noconflict.js', $custom_tag);
			$app->set('jquery', true);
		} else if(APP_VERSION > 2.5){
			
			JHtml::_('jquery.framework');
		}

		if(!in_array('ui', $plugins) && in_array('ui', $params['libs'])){

			if(APP_VERSION <= 2.5){

				$theme = (!empty($params['theme']) && strcmp($params['theme'], 'default') != 0) ? JFile::makeSafe($params['theme']) : 'start';
				
				if($theme != 'none'){

					$document->addStyleSheet(CJLIB_URI.'/jquery/themes/'.$theme.'/jquery-ui.css');
					CJFunctions::add_script_to_document($document, 'jquery-ui.min.js', $custom_tag);
				} else {
					
					$document->addStyleSheet(CJLIB_URI.'/jquery/themes/no-theme/jquery-ui.css');
					CJFunctions::add_script_to_document($document, 'jquery-ui-core.min.js', $custom_tag);
				}
			} else{
				
				JHtml::_('jquery.ui', array('core', 'sortable'));
			}
			
			$plugins[] = 'ui';
		}
		
		if(!in_array('extras', $plugins)){
			
			if(in_array('extras', $params['libs'])){
				
				$document->addStyleSheet(CJLIB_URI.'/jquery/jquery.extras.min.css');
				CJFunctions::add_script_to_document($document, 'jquery.extras.min.js', $custom_tag);
				CJFunctions::add_script_to_document($document, 'jquery.form.min.js', $custom_tag);
				$plugins[] = 'extras';
				$plugins[] = 'form';
				$plugins[] = 'validate';
				$plugins[] = 'treeview';
			} else {
	
				/** deprecated */
				if(in_array('menu', $params['libs'])){
				
					CJFunctions::add_script_to_document($document, 'jquery.extras.min.js', $custom_tag);
					$document->addStyleSheet(CJLIB_URI.'/jquery/jquery.extras.min.css');
					$plugins[] = 'extras';
				}
			}
		}

		if(!in_array('json', $plugins) && in_array('json', $params['libs'])){
		
			CJFunctions::add_script_to_document($document, 'json2.js', $custom_tag);
			$plugins[] = 'json';
		}
		
		if(!in_array('validate', $plugins) && in_array('validate', $params['libs'])){
			
			CJFunctions::add_script_to_document($document, 'jquery.validate.min.js', $custom_tag);
			$plugins[] = 'validate';
		}
		
		if(!in_array('rating', $plugins) && in_array('rating', $params['libs'])){
			
			CJFunctions::add_script_to_document($document, 'jquery.raty.min.js', $custom_tag);
			$plugins[] = 'rating';
		}

		if(!in_array('form', $plugins) && in_array('form', $params['libs'])){

			CJFunctions::add_script_to_document($document, 'jquery.form.min.js', $custom_tag);
			$plugins[] = 'form';
		}

// 		if(!in_array('morris', $plugins) && in_array('morris', $params['libs'])){
		
// 			CJFunctions::add_script_to_document($document, 'raphael-min.js', $custom_tag);
// 			CJFunctions::add_script_to_document($document, 'morris.min.js', $custom_tag);
// 			$plugins[] = 'morris';
// 		}
		
		if(!in_array('datepicker', $plugins) && in_array('datepicker', $params['libs'])){
		
			$document->addStyleSheet(CJLIB_URI.'/jquery/datepicker.css');
			CJFunctions::add_script_to_document($document, 'bootstrap-datepicker.js', $custom_tag);
			$plugins[] = 'datepicker';
		}

		if(!in_array('treeview', $plugins) && in_array('treeview', $params['libs'])){
		
			$document->addStyleSheet(CJLIB_URI.'/jquery/jquery.treeview.css');
			CJFunctions::add_script_to_document($document, 'jquery.treeview.js', $custom_tag);
			$plugins[] = 'treeview';
		}
		
		if(!in_array('colorbox', $plugins) && in_array('colorbox', $params['libs'])){
		
			$document->addStyleSheet(CJLIB_URI.'/jquery/colorbox.css');
			CJFunctions::add_script_to_document($document, 'jquery.colorbox.min.js', $custom_tag);
			$plugins[] = 'colorbox';
		}

		if(!in_array('social', $plugins) && in_array('social', $params['libs'])){
		
			$document->addStyleSheet(CJLIB_URI.'/jquery/social/socialcount-with-icons.min.css');
			CJFunctions::add_script_to_document($document, 'socialcount.min.js', $custom_tag, CJLIB_URI.'/jquery/social/');
			$plugins[] = 'social';
		}

		if(!in_array('chosen', $plugins) && in_array('chosen', $params['libs'])){
		
			$document->addStyleSheet(CJLIB_URI.'/jquery/chosen/chosen.css');
			CJFunctions::add_script_to_document($document, 'chosen.jquery.min.js', $custom_tag, CJLIB_URI.'/jquery/chosen/');
			$plugins[] = 'chosen';
		}
		
		if(!in_array('tags', $plugins) && in_array('tags', $params['libs'])){
		
			$document->addStyleSheet(CJLIB_URI.'/jquery/cj.tags.min.css');
			CJFunctions::add_script_to_document($document, 'cj.tags.js', $custom_tag);
			$plugins[] = 'tags';
		}
		
		if(!in_array('backbone', $plugins) && in_array('backbone', $params['libs'])){
		
			CJFunctions::add_script_to_document($document, 'underscore-min.js', $custom_tag);
			CJFunctions::add_script_to_document($document, 'backbone-min.js', $custom_tag);
			$plugins[] = 'backbone';
		}
			
		if(!in_array('fontawesome', $plugins) && in_array('fontawesome', $params['libs']) && !CJLib::$_bootstrap_loaded){

			$document->addCustomTag('<link rel="stylesheet" href="'.CJLIB_URI.'/bootstrap/css/font-awesome.min.css" type="text/css">');
			$document->addCustomTag('<!--[if IE 7]><link rel="stylesheet" href="'.CJLIB_URI.'/bootstrap/css/font-awesome-ie7.min.css" type="text/css"><![endif]-->');
			$plugins[] = 'fontawesome';
		}
		
		$app->jqueryplugins = $plugins;
	}
	
	public static function add_script_to_document($doc, $script, $custom_tag, $base = null){
		
		$base = !$base ? CJLIB_URI.'/jquery/' : $base;
		if($custom_tag){
			
			$doc->addCustomTag('<script src="'.$base.$script.'" type="text/javascript"></script>');
		} else {
			
			$doc->addScript($base.$script);
		}
	}

	public static function add_css_to_document($doc, $css, $custom_tag){
	
		if($custom_tag){
				
			$doc->addCustomTag('<link rel="stylesheet" href="'.$css.'" type="text/css">');
		} else {
				
			$doc->addStyleSheet($css);
		}
	}
	
	/**
	 * Returns the editor html markup based on the <code>editor</code> type choosen, bbcode - BBCode Editor, wysiwyg - Joomla default editor, none - plain text area.
	 *  
	 * @param string $editor editor type
	 * @param int $id id of the editor/textarea tag
	 * @param string $name name of the editor/textarea tag
	 * @param string $html default content to be populated in editor/textarea
	 * @param int $rows number of rows of the textarea
	 * @param int $cols number of columns of the textarea
	 * @param string $width width of the editor in pixels or percentage  
	 * @param string $height height of the editor in pixels or percentage
	 * @param string $class css class applied to the editor 
	 * @param string $style style applied to the editor
	 * 
	 * @return string output of the loaded editor markup 
	 */
	public static function load_editor($editor, $id, $name, $html, $rows, $cols, $width=null, $height=null, $class=null, $style=null, $custom_tag = false){
	
		$style = $style ? ' style="'.$style.'"' : '';
		$class = $class ? ' class="'.$class.'"' : '';
		$width = $width ? $width : '450px';
		$height = $height ? $height : '200px';
	
		if($editor == 'bbcode') {
				
			$content = '<style type="text/css"><!-- .markItUpHeader ul { margin: 0; } .markItUpHeader ul li	{ list-style:none; float:left; position:relative; background: none;	line-height: 100%; margin: 0; padding: 0; } --></style>';
			$content .= '<div style="width: '.$width.';"><textarea name="'.$name.'" id="'.$id.'" rows="'.$rows.'" cols="'.$cols.'"'.$style.$class.'>'.$html.'</textarea></div>';
				
			$document = JFactory::getDocument();

			CJFunctions::add_script_to_document($document, 'jquery.markitup.js', $custom_tag, CJLIB_URI.'/lib/markitup/');
			CJFunctions::add_script_to_document($document, 'set.js', $custom_tag, CJLIB_URI.'/lib/markitup/sets/bbcode/');
				
			$document->addStyleSheet(CJLIB_URI.'/lib/markitup/skins/markitup/style.css');
			$document->addStyleSheet(CJLIB_URI.'/lib/markitup/sets/bbcode/style.css');
				
			$document->addScriptDeclaration('jQuery(document).ready(function($){$("#'.$id.'").markItUp(cjbbcode)});;');
		} else if($editor == 'wysiwyg' || $editor == 'default') {
				
			$jeditor = JFactory::getEditor();
			$content = '<div style="overflow: hidden; clear: both;">'.$jeditor->display( $name, $html, $width, $height, $cols, $rows, true, $id ).'</div>';
		} else {
			
			$content = '<textarea name="'.$name.'" id="'.$id.'" rows="5" cols="50"'.$style.$class.'>'.$html.'</textarea>';
		}
	
		return $content;
	}
	
	/**
	 * Processes BBCode content if the <code>bbcode</code> value is set, else returns <code>content</code>
	 * 
	 * @param string $content html or bbcode content
	 * @param boolean $bbcode flag indicating the content type is bbocde or not 
	 * @param boolean $process_content_plugins flag to enable processing of Joomla(r) content plugins
	 */
	public static function process_html($content, $bbcode = false, $process_content_plugins = false, $autolink = true){
	
		if($bbcode){
				
			if(!function_exists('BBCode2Html')){
	
				require_once CJLIB_PATH.DS.'lib'.DS.'markitup'.DS.'bbcodeparser.php';
			}
				
			$content = BBCode2Html($content);
		}

		if($autolink){
			
			require_once 'lib_autolink.php';
			$content = autolink_urls($content, 50, ' rel="nofollow"');
		}
				
		if($process_content_plugins){

			$content = JHTML::_('content.prepare', $content);
		}
		
		return $content;
	}
	
	/**
	 * Function to get available theme names from jquery ui library
	 *
	 * @return array list of theme names available on jquery ui library
	 */
	public static function get_ui_theme_names(){

		$themes = array();
		$path = CJLIB_PATH.DS.'jquery'.DS.'themes';

		if(file_exists($path)){
				
			$themes = JFolder::folders($path);
		}

		return $themes;
	}

	/**
	 * Award points to the user using selected points system. The user id and required parameters should be passed based on points system selected.
	 *
	 * Parameters array should be an associative array which can include
	 * <ul>
	 * 	<li>function: the function name used to award points.</li>
	 * 	<li>points: points awarded to the user. For jomsocial this has no effect as the points are taken from xml rule.</li>
	 * 	<li>reference: the reference string for AUP rule</li>
	 * 	<li>info: Brief information about this point.</li>
	 * </ul>
	 *
	 * @param string $system
	 * @param int $userid
	 * @param array $params
	 */
	public static function award_points($system, $userid, $params=array()){

		switch ($system){
			
			case 'cjblog':
				
				$api = JPATH_ROOT.DS.'components'.DS.'com_cjblog'.DS.'api.php';
				
				if(file_exists($api)){
					
					include_once $api;
					$points = !empty($params['points']) ? $params['points'] : 0;
					$reference = !empty($params['reference']) ? $params['reference'] : null;
					$description = !empty($params['info']) ? $params['info'] : null;
					
					CjBlogApi::award_points($params['function'], $userid, $points, $reference, $description);
				}
				
				break;

			case 'jomsocial':

				$api = JPATH_SITE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'userpoints.php';
				if( file_exists($api) && !empty($params['function']) ){

					include_once $api;
					CuserPoints::assignPoint( $params['function'], $userid );
				}
				break;

			case 'aup':

				$api = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
				if ( file_exists($api) && !empty($params['function']) && isset($params['reference']) && isset($params['info']) ){

					require_once $api;
					$aupid = AlphaUserPointsHelper::getAnyUserReferreID( $userid );
					AlphaUserPointsHelper::newpoints( $params['function'], $aupid, $params['reference'], $params['info'], $params['points'] );
				}
				break;

			case 'touch':

				$api = JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'api.php';
				if ( file_exists($api) && !empty($params['points']) ){

					require_once $api;
					JSCommunityApi::increaseKarma( $userid, $params['points'] );
				}
				break;
		}
	}

	/**
	 * Streams activity to selected extension stream. The parameters should include required values based on extension as associative array
	 *
	 * <ul>
	 * <li>command: command for jomsocial activity, mostly component_name.action</li>
	 * <li>title: title of the stream</li>
	 * <li>description: short description. if full text is passed it will be stripped upto length <code>$params['length']</code></li>
	 * <li>length: max length of the activity description</li>
	 * <li>href: url of the stream</li>
	 * <li>icon: icon to be used for mighty touch stream</li>
	 * <li>component: component name i.e.com_yourcomponentname</li>
	 * <li>group: group name for touch stream. ex. Articles</li>
	 * </ul>
	 *
	 * @param string $system Component to stream
	 * @param int $userid User id
	 * @param array $params params based on component type
	 */
	public static function stream_activity($system, $userid, $params=array()){

		switch($system){

			case 'jomsocial':

				$api = JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php';
				if( file_exists($api) && !empty($params['title']) && !empty($params['command']) ){

					include_once $api;
					CFactory::load('libraries', 'activities');
						
					$act = new stdClass();
					$act->cmd			= 'wall.write';
					$act->target		= 0;
					$act->app			= 'wall';
					$act->cid			= 0;
					$act->comment_id	= CActivities::COMMENT_SELF;
					$act->like_id		= CActivities::LIKE_SELF;
					$act->actor			= $userid;
					$act->title			= $params['title'];
					$act->comment_type	= $params['command'];
					$act->like_type		= $params['command'];
					$act->access		= 0;
						
					if( !empty($params['description']) && !empty($params['length']) ){

						$content = CJFunctions::substrws( $params['description'], $params['length'] );
						
						if(!empty($params['href'])){
							
							$act->content = $content.'
									<div style="margin-top: 5px;">
										<div style="float: right; font-weight: bold; font-size: 12px;">
											<a href="'.$params['href'].'">'.JText::_('COM_CJLIB_READ_MORE').'</a>
										</div>
										<div style="clear: both;"></div>
									</div>';
						} else {
							
							$act->content = $content;
						}
					}

					CActivityStream::add($act);
				}
				break;

			case 'touch':

				$api = JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'api.php';
				if( file_exists($api) && !empty($params['title']) && !empty($params['icon']) && !empty($params['component']) && !empty($params['group']) ){
						
					require_once $api;
					$capi = new JSCommunityApi();
					$capi->registerActivity(0, $params['title'], $userid, $params['icon'], 'user', null, $params['component'], '', $params['group']);
				}
				break;
		}
	}

	/**
	 * Gets the user avatar of the selected <code>system</code>
	 * 
	 * @param string $system Avatar system to be used
	 * @param int $userid user id
	 * @param string $username username or name
	 * @param int $height height of the avatar
	 * 
	 * @return string user avatar
	 */
	public static function get_user_avatar($system, $userid, $displayname = 'username', $height = 48, $email = null, $attribs = array(), $img_attribs = array()){
		
		$db = JFactory::getDBO();
		$avatar = '';
		
		switch ( $system ) {
			
			case 'cjblog':
				
				$api = JPATH_ROOT.DS.'components'.DS.'com_cjblog'.DS.'api.php';
				
				if(file_exists($api)){
					
					include_once $api;
					$avatar = CjBlogApi::get_user_avatar($userid, $height, $displayname, $attribs, $img_attribs);
				}
				
				break;
			 
			case 'jomsocial':
				 
				include_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'defines.community.php' );
				require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
				require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'helpers' . DS . 'string.php' );

				$user = CFactory::getUser( $userid );
				$name = CStringHelper::escape( $user->getDisplayName() );
				$userLink = CRoute::_('index.php?option=com_community&view=profile&userid='.$userid);
				$img_attribs['height'] = $height.'px';
				$avatar_image = JHtml::image($user->getThumbAvatar(), $name, $img_attribs);
				
				$avatar = $userid > 0 ? JHtml::link($userLink, $avatar_image, $attribs) : JHtml::link('#', $avatar_image, $attribs);

				break;
		
			case 'cb':
				
				global $_CB_framework, $_CB_database, $ueConfig, $mainframe;
				
				$api = JPATH_ADMINISTRATOR.'/components/com_comprofiler/plugin.foundation.php';
				
				if (!is_file($api)) return;
				require_once ($api);
				
				cbimport ( 'cb.database' );
				cbimport ( 'cb.tables' );
				cbimport ( 'cb.field' );
				cbimport ( 'language.front' );
				
				outputCbTemplate( $_CB_framework->getUi() );
				$img_attribs['height'] = $height.'px';
				
				if($userid > 0){
					
					$cbUser = CBuser::getInstance( $userid );
					
					if ( $cbUser !== null ) {
						
						$avatar = $cbUser->getField( 'avatar', null, 'php', 'profile', 'list' );
						$name = $cbUser->getField( 'name');
						$link = cbSef( 'index.php?option=com_comprofiler&amp;task=userProfile&amp;user=' . ( (int) $userid ) . getCBprofileItemid( true, false ) );
						
						$avatar = JHtml::link($link, Jhtml::image($avatar['avatar'], '', $img_attribs), $attribs);
					}
				} else {
					
					if ($height<=90) {
						
						$avatar = JHtml::link('#', Jhtml::image(selectTemplate().'images/avatar/tnnophoto_n.png', '', $img_attribs), $attribs);
					} else {
						
						$avatar = JHtml::link('#', Jhtml::image(selectTemplate().'images/avatar/nophoto_n.png', '', $img_attribs), $attribs);
					}
				}
		
				break;
		
			case 'touch':
				 
				$avatarLoc = JURI::base(true) . '/index2.php?option=com_community&amp;controller=profile&amp;task=avatar&amp;width=' . $height . '&amp;height=' . $height . '&amp;user_id=' . $userid . '&amp;no_ajax=1';
				$avatar = '<img src="'.$avatarLoc.'" style="border: 1px solid #cccccc; height: "'.$height.'"px;" alt=""/>';
				$link = JRoute::_("index.php?option=com_community&view=profile&user_id={$userid}&Itemid=".JRequest::getInt('Itemid'));
				$avatar = '<a href="' . $link . '">' . $avatar . '</a>';
		
				break;
		
			case 'gravatar':

				if(null == $email && $userid > 0){
					
					$strSql = 'SELECT email FROM #__users WHERE id=' . $userid;
					$db->setQuery($strSql);
					$email = $db->loadResult();
				}
		
				$avatar = '<img src="http://www.gravatar.com/avatar/'.md5( strtolower( trim( $email ) ) ).'?s='.$height.'&d=mm&r=g"/>';
		
				break;
		
			case 'kunena':
				
				if(CJFunctions::_initialize_kunena()){
					
					$class = 'avatar';
					$user = KunenaFactory::getUser($userid);
					
					$avatarHtml = $user->getAvatarImage($class, $height, $height);
					$avatar = $user->getLink($avatarHtml);
				} else if(file_exists(JPATH_ROOT.DS.'components'.DS.'com_kunena'.DS.'class.kunena.php')){
					
					require_once (JPATH_ROOT.DS.'components'.DS.'com_kunena'.DS.'class.kunena.php');
					require_once (JPATH_ROOT.DS.'components'.DS.'com_kunena'.DS.'lib'.DS.'kunena.link.class.php');
			
					$kunena_user = KunenaFactory::getUser ( ( int ) $userid );
					$displayname = $kunena_user->getName(); // Takes care of realname vs username setting
					$avatarlink = $kunena_user->getAvatarLink ( '', $height, $height );
					$avatar = CKunenaLink::GetProfileLink($userid, $avatarlink, $displayname);
				}
				
				break;
		
			case 'aup':
				 
				$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
		
				if ( file_exists($api_AUP)) {
					 
					require_once ($api_AUP);
		
					$avatar = AlphaUserPointsHelper::getAupAvatar($userid, 1, $height, $height);
				}
		
				break;
		}
		
		return $avatar;
	}
	
	public static function load_users($system, $ids){
		
		if(empty($ids)) return;
		
		switch ($system){
			
			case 'cjblog':
				
				$api = JPATH_ROOT.DS.'components'.DS.'com_cjblog'.DS.'api.php';
				
				if(file_exists($api)) {
					
					require_once $api;
					CjBlogApi::load_users($ids);
				}
				
				break;
				
			case 'kunena':
				
				if(CJFunctions::_initialize_kunena()){
					
					KunenaUserHelper::loadUsers($ids);
				}
				
			case 'cb':
				
				$api = JPATH_ADMINISTRATOR.'/components/com_comprofiler/plugin.foundation.php';
				
				if (!is_file($api)) return;
				require_once ($api);
				
				cbimport ( 'cb.database' );
				cbimport ( 'cb.tables' );
				cbimport ( 'language.front' );
				cbimport ( 'cb.tabs' );
				cbimport ( 'cb.field' );
				global $ueConfig;
				
				CBuser::advanceNoticeOfUsersNeeded($ids);
				
				break;
				
		}
	}
	
	/**
	 * Gets the user profile url of selected <code>system</code>. Currently supported systems are <br><br> 
	 * 
	 * JomSocial - jomsocial, Community Builder - cb, Touch - touch, Kunena - kunena, Alpha User Points - aup
	 * 
	 * @param string $system User profile system
	 * @param int $userid user id
	 * @param string $username User name to be used to display with link
	 * @param array $links array of links for mighty touch
	 * @param path_only boolean want to retrive just the url or the full html hyperlink markup?
	 * 
	 * @return string user profile url
	 */
	public static function get_user_profile_link($system, $userid = 0, $username = 'Guest', $links = array(), $alias = null, $path_only = false, $attribs = array()){
		
		$link = null;
		
		switch ( $system ) {
			
			case 'cjblog':
				
				$api = JPATH_ROOT.DS.'components'.DS.'com_cjblog'.DS.'api.php';
				
				if(file_exists($api)){
					
					require_once $api;
					$link = CjBlogApi::get_user_profile_url($userid, 'name', $path_only, $attribs);
				}
				
				break;
			
			case 'jomsocial':
				
				$jspath = JPATH_BASE.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php';
				
				if(file_exists($jspath)) {
					
					include_once($jspath);
					
					if($path_only){
					
						$link = CRoute::_('index.php? option=com_community&view=profile&userid='.$userid);
					} else {
						
						$link = JHtml::link(CRoute::_('index.php? option=com_community&view=profile&userid='.$userid), $username, $attribs);
					}
				}
				
				break;
				
			case 'cb':

				global $_CB_framework, $_CB_database, $ueConfig, $mainframe;
				
				$api = JPATH_ADMINISTRATOR.'/components/com_comprofiler/plugin.foundation.php';
				
				if (!is_file($api)) return;
				require_once ($api);
				
				cbimport ( 'cb.database' );
				cbimport ( 'cb.tables' );
				cbimport ( 'language.front' );
				cbimport ( 'cb.field' );
				
				$url = cbSef( 'index.php?option=com_comprofiler&amp;task=userProfile&amp;user=' . ( (int) $userid ) . getCBprofileItemid( true, false ) );
				
				if($path_only){
				
					$link = $url;
				} else {
					
					$link = JHtml::link($url, $username, $attribs);
				}
				
				break;
				
			case 'kunena':
				
				if(CJFunctions::_initialize_kunena() && $userid > 0) {
					
					$user = KunenaFactory::getUser($userid);
					if ($user === false) break;
					
					if($path_only){
					
						$link = KunenaRoute::_('index.php?option=com_kunena&func=profile&userid='.$user->userid, true);
					} else {
						
						$link = JHtml::link(KunenaRoute::_('index.php?option=com_kunena&func=profile&userid='.$user->userid, true), $user->name, $attribs);
					}
				}
				
				break;
				
			case 'aup':
				
				$api_AUP = JPATH_SITE.DS.'components'.DS.'com_alphauserpoints'.DS.'helper.php';
				
				if ( file_exists($api_AUP)) {
					
					require_once ($api_AUP);
					
					if($path_only){
					
						$link = AlphaUserPointsHelper::getAupLinkToProfil($userid);
					} else {
						
						$link = JHtml::link(AlphaUserPointsHelper::getAupLinkToProfil($userid), $username, $attribs);
					}
				}
				
				break;
		}
		
		return (null == $link) ? $username : $link;
	}
	
	public static function get_localized_date($strdate, $format = 'Y-m-d'){
		
		if(empty($strdate) || $strdate == '0000-00-00 00:00:00'){
				
			return JText::_('LBL_NA');
		}
		
		jimport('joomla.utilities.date');
		$date = JFactory::getDate($strdate);
		
		return $date->format($format, true);
	}
	
	/**
	 * Gets the human friendly date string from a date
	 * 
	 * @param string $strdate date
	 * 
	 * @return string formatted date string
	 */
	public static function get_formatted_date($strdate) {
		
		if(empty($strdate) || $strdate == '0000-00-00 00:00:00'){
			
			return JText::_('LBL_NA');
		}
		
		jimport('joomla.utilities.date');
		$user = JFactory::getUser();
		$tz = 0;
		
		if($user->get('id')) {
			
			$tz = $user->getParam('timezone');
		} else {
			
			$conf = JFactory::getConfig();
			$tz = $conf->get('config.offset');
		}
	
		// Given time
		$date = new JDate($strdate, $tz);
		$compareTo = new JDate('now', $tz);
		
		$diff = $compareTo->toUnix() - $date->toUnix();
		$dayDiff = floor($diff/86400);

		if($dayDiff == 0) {

			if($diff < 60) {
				
				return JText::_('COM_CJLIB_JUST_NOW');
			} elseif($diff < 120) {
				
				return JText::_('COM_CJLIB_ONE_MINUTE_AGO');
			} elseif($diff < 3600) {
				
				return JText::sprintf('COM_CJLIB_N_MINUTES_AGO', floor($diff/60));
			} elseif($diff < 7200) {
				
				return JText::_('COM_CJLIB_ONE_HOUR_AGO');
			} elseif($diff < 86400) {
				
				return JText::sprintf('COM_CJLIB_N_HOURS_AGO', floor($diff/3600));
			}
		} elseif($dayDiff == 1) {
			
			return JText::_('COM_CJLIB_YESTERDAY');
		} elseif($dayDiff < 7) {
			
			return JText::sprintf('COM_CJLIB_N_DAYS_AGO', $dayDiff);
		} elseif($dayDiff == 7) {
			
			return JText::_('COM_CJLIB_ONE_WEEK_AGO');
		} elseif($dayDiff < (7*6)) {
			
			return JText::sprintf('COM_CJLIB_N_WEEKS_AGO', ceil($dayDiff/7));
		} elseif($dayDiff > 30 && $dayDiff <= 60) {
			
			return JText::_('COM_CJLIB_ONE_MONTH_AGO');
		} elseif($dayDiff < 365) {
			
			return JText::sprintf('COM_CJLIB_N_MONTHS_AGO', ceil($dayDiff/(365/12)));
		} else {
			
			$years = round($dayDiff/365);
			
			if($years == 1){
				
				return JText::sprintf('COM_CJLIB_ONE_YEAR_AGO', round($dayDiff/365));
			}else{
				
				return JText::sprintf('COM_CJLIB_N_YEARS_AGO', round($dayDiff/365));
			}
		}
	}
	
	/**
	 * Gets the difference between two dates in human readable format.
	 * 
	 * @param string $date1
	 * @param string $date2
	 */
	public static function get_date_difference($strdate1, $strdate2){
		
		$diff = strtotime($strdate2) - strtotime($strdate1);
		$days = floor($diff / 86400);
		$hours = floor(($diff % 86400) / 3600);
		$minutes = floor(($diff % 3600) / 60);
		$seconds = floor($diff % 60);
		
		if($diff <= 0){
			
			return JText::_('COM_CJLIB_NOT_COMPLETED');
		}else if($days > 0) {
			
			return JText::sprintf('COM_CJLIB_DATE_DIFF_DAYS', $days, $hours, $minutes, $seconds);
		} else if($hours > 0){
			
			return JText::sprintf('COM_CJLIB_DATE_DIFF_HOURS', $hours, $minutes, $seconds);
		} else {
			
			return JText::sprintf('COM_CJLIB_DATE_DIFF_MINUTES', $minutes, $seconds);
		}
	}

	/**
	 * word-sensitive substring function with html tags awareness
	 *
	 * @param string text The text to cut
	 * @param int len The maximum length of the cut string
	 * @param array Array of tags to exclude
	 *
	 * @return string The modified html content
	 */
	public static function substrws( $text, $len=180, $tags=array()) {

		if(function_exists('mb_strlen')){
			
			if( (mb_strlen($text, 'UTF-8') > $len) ) {
			
				$whitespaceposition = mb_strpos($text, ' ', $len, 'UTF-8')-1;
			
				if( $whitespaceposition > 0 ) {
			
					$chars = count_chars(mb_substr($text, 0, $whitespaceposition + 1, 'UTF-8'), 1);
			
					if (!empty($chars[ord('<')]) && $chars[ord('<')] > $chars[ord('>')]){
							
						$whitespaceposition = mb_strpos($text, '>', $whitespaceposition, 'UTF-8') - 1;
					}
			
					$text = mb_substr($text, 0, $whitespaceposition + 1, 'UTF-8');
				}
			
				// close unclosed html tags
				if( preg_match_all("|<([a-zA-Z]+)|",$text,$aBuffer) ) {
			
					if( !empty($aBuffer[1]) ) {
			
						preg_match_all("|</([a-zA-Z]+)>|",$text,$aBuffer2);
			
						if( count($aBuffer[1]) != count($aBuffer2[1]) ) {
			
							foreach( $aBuffer[1] as $index => $tag ) {
			
								if( empty($aBuffer2[1][$index]) || $aBuffer2[1][$index] != $tag){
			
									$text .= '</'.$tag.'>';
								}
							}
						}
					}
				}
			}
		} else {
			
			if( (strlen($text) > $len) ) {
			
				$whitespaceposition = strpos($text, ' ', $len)-1;
			
				if( $whitespaceposition > 0 ) {
			
					$chars = count_chars(substr($text, 0, $whitespaceposition + 1), 1);
			
					if ($chars[ord('<')] > $chars[ord('>')]){

						$whitespaceposition = strpos($text, '>', $whitespaceposition) - 1;
					}
			
					$text = substr($text, 0, $whitespaceposition + 1);
				}
			
				// close unclosed html tags
				if( preg_match_all("|<([a-zA-Z]+)|",$text,$aBuffer) ) {
			
					if( !empty($aBuffer[1]) ) {
			
						preg_match_all("|</([a-zA-Z]+)>|",$text,$aBuffer2);
			
						if( count($aBuffer[1]) != count($aBuffer2[1]) ) {
			
							foreach( $aBuffer[1] as $index => $tag ) {
			
								if( empty($aBuffer2[1][$index]) || $aBuffer2[1][$index] != $tag){
			
									$text .= '</'.$tag.'>';
								}
							}
						}
					}
				}
			}
		}
		
		return preg_replace('#<p[^>]*>(\s|&nbsp;?)*</p>#', '', $text);;
	}

	/**
	 * Gets the ip address of the user from request
	 *
	 * @return string ip address
	 */
	public static function get_user_ip_address() {

		$ip = '';

		if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) AND strlen($_SERVER['HTTP_X_FORWARDED_FOR'])>6 ){
				
			$ip = strip_tags($_SERVER['HTTP_X_FORWARDED_FOR']);
		}elseif( !empty($_SERVER['HTTP_CLIENT_IP']) AND strlen($_SERVER['HTTP_CLIENT_IP'])>6 ){
				
			$ip = strip_tags($_SERVER['HTTP_CLIENT_IP']);
		}elseif(!empty($_SERVER['REMOTE_ADDR']) AND strlen($_SERVER['REMOTE_ADDR'])>6){
				
			$ip = strip_tags($_SERVER['REMOTE_ADDR']);
		}

		return trim($ip);
	}
	
	/**
	 * Gets the array containing location details matching the ip address.
	 * 
	 * @param string $ip IP address
	 * 
	 * @return array An array containing city, country and country code (country_code) information
	 */
	public static function get_user_location($ip){
		
		$info = array();
		$geoLiteDB = JPATH_ROOT.DS.'media'.DS.'com_cjlib'.DS.'geoip'.DS.'GeoLiteCity.dat';
		$info['continent'] = 'Unknown';
		$info['country'] = "Unknown";
		$info['country_code'] = "00";
		$info['country_code_3'] = "000";
		$info['city'] = "Unknown";
		$info['lattitude'] = '';
		$info['longitude'] = '';
		
		if(file_exists($geoLiteDB)){
			
			if(!function_exists('geoip_record_by_addr')){
			
				include_once CJLIB_PATH.DS.'framework'.DS.'geoipcity.inc';
			}
			
			include_once CJLIB_PATH.DS.'framework'.DS.'geoipregionvars.php';
			
			$gi = geoip_open($geoLiteDB, GEOIP_STANDARD);
			$record = geoip_record_by_addr($gi, $ip);
			
			if(!empty($record)){
				
				$info['country'] = $record->country_name;
				$info['country_code'] = $record->country_code; 
				$info['country_code_3'] = $record->country_code3;
				$info['city'] = $record->city;
				$info['lattitude'] = $record->latitude;
				$info['longitude'] = $record->longitude;
			}
			
			geoip_close($gi);
		} else {
		
			$xml = file_get_contents("http://api.hostip.info/?ip=".trim($ip));
			preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si", $xml, $match);
		
			if(!empty($match[2])){
				
				$info['city'] = $match[2];
			} else {
				
				$info['city'] = '';
			}
		
			preg_match("@<countryName>(.*?)</countryName>@si", $xml, $matches);
			$info['country'] = $matches[1];
		
			preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si", $xml, $cc_match);
			$info['country_code'] = $cc_match[1];
		}
	
		return $info;
	}
	
	/**
	 * Gets the clean content from the request variable named <code>var</code>. If the second parameter passed as false, html tags will be stripped out.
	 *
	 * @param string $var
	 * @param boolean $html
	 * @return Ambigous <string, mixed>
	 */
	public static function get_clean_var($var, $html = true, $default = ''){
		
		$value = $html ? JRequest::getVar($var, $default, 'post', 'string', JREQUEST_ALLOWRAW) : JRequest::getVar($var, $default, 'post', 'string');
		$value = empty($_POST[$var]) ? $default : $_POST[$var];
		
		return CJFunctions::clean_value($value);
	}
	
	/**
	 * Gets the clean content from the value given. If the second parameter passed as false, html tags will be stripped out.
	 *
	 * @param string $var
	 * @param boolean $html
	 * @return Ambigous <string, mixed>
	 */
	public static function clean_value($value, $html=true){
		
		$filter = new CleanXSS();
		
		return $filter->sanitize($value);
	}

	/**
	 * Returns unicode alias string from the <code>title</code> passed as an argument. If the Joomla version is less than 1.6, the function will gracefully degrades and outputs normal alias.
	 *
	 * @param string $title
	 */
	public static function get_unicode_alias($title){

		return (APP_VERSION == '1.5') ?  JFilterOutput::stringURLSafe($title) : JFilterOutput::stringURLUnicodeSlug($title);
	}

	/**
	 * A wrapper function for the Joomla mail API to send emails.
	 *
	 * @param string $from from email address
	 * @param string $fromname name of the sender
	 * @param string $recipient reciepient email
	 * @param string $subject email subject
	 * @param string $body body of the email
	 * @param boolean $mode true if html mode enabled, false otherwise
	 * @param string $cc email addresses in cc
	 * @param string $bcc email addresses in bcc
	 * @param string $attachment attachment
	 * @param string $replyto replyto email address
	 * @param string $replytoname reply to name
	 *
	 * @return mixed True if successful, a JError object otherwise
	 */
	public static function send_email($from, $fromname, $recipient, $subject, $body, $mode=0, $cc=null, $bcc=null, $attachment=null, $replyto=null, $replytoname=null){

		// Get a JMail instance
		$mail = JFactory::getMailer();

		$mail->setSender(array($from, $fromname));
		$mail->setSubject($subject);
		$mail->setBody($body);

		// Are we sending the email as HTML?
		if ($mode) {
				
			$mail->IsHTML(true);
		}

		if(!is_array($recipient)){
		
			$recipient = explode(',', $recipient);
		}

		$mail->addRecipient($recipient);
		$mail->addCC($cc);
		$mail->addBCC($bcc);
		$mail->addAttachment($attachment);

		// Take care of reply email addresses
		if (is_array($replyto)) {
				
			$numReplyTo = count($replyto);
				
			for ($i=0; $i < $numReplyTo; $i++){

				$mail->addReplyTo(array($replyto[$i], $replytoname[$i]));
			}
		} elseif (isset($replyto)) {
				
			$mail->addReplyTo(array($replyto, $replytoname));
		}

		return  $mail->Send();
	}

	/**
	 * Loads the modules assigned to position <code>$position</code>
	 *
	 * @param string $position
	 * @return string The output of the script
	 */
	public static function load_module_position($position) {

		jimport( 'joomla.application.module.helper' );
		
		if(JModuleHelper::getModules($position)) {
				
			$document	= JFactory::getDocument();
			$renderer	= $document->loadRenderer('modules');
			$options	= array('style' => 'xhtml');
				
			return $renderer->render($position, $options, null);
		}else {
				
			return '';
		}
	}

	/**
	 * Generate a random character string
	 *
	 * @param int $length length of the string to be generated
	 * @param string $chars characters to be considered, default alphanumeric characters.
	 *
	 * @return string randomly generated string
	 */
	public static function generate_random_key($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'){

		// Length of character list
		$chars_length = (strlen($chars) - 1);
			
		// Start our string
		$string = $chars{rand(0, $chars_length)};

		// Generate random string
		for ($i = 1; $i < $length; $i = strlen($string))
		{
			// Grab a random character from our list
			$r = $chars{rand(0, $chars_length)};

			// Make sure the same two characters don't appear next to each other
			if ($r != $string{$i - 1}) $string .=  $r;
		}

		// Return the string
		return $string;
	}

	/**
	 * Throws new exception with message and error code for j1.6 or above, calls <code>JError::raiseError</code> otherwise.
	 *
	 * @param string $msg message
	 * @param int $code error code
	 * 
	 * @throws Exception
	 */
	public static function throw_error($msg, $code){

		if(APP_VERSION == '1.5'){
				
			JError::raiseError( $code, $msg);
		}else{
				
			throw new Exception($msg, $code);
		}
	}

	/**
	 * Convert special characters to HTML entities with UTF-8 encoding.
	 * 
	 * @param string $var content to be escaped
	 */
	public static function escape($var){

		return htmlspecialchars($var, ENT_COMPAT, 'UTF-8');
	}

	/**
	 * Triggers a Joomla event
	 * 
	 * @param string $group The plugin type, relates to the sub-directory in the plugins directory
	 * @param string $event The event to trigger
	 * @param array $params An array of arguments
	 */
	public static function trigger_event($group, $event, $params=null){

		JPluginHelper::importPlugin( $group );

		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger($event, $params);
	}
	
	/**
	 * Returns menu id of active menu. If the <code>itemid</code> is set, Itemid request variable name is prepended for use in JRoute urls.
	 * 
	 * @param boolean $itemid Returns Itemid appended text if set to true else int id of active menu
	 * @param string $url The url from which the menu id to be retrieved instead of active menu item
	 * @param boolean $cat_request If true, catid from request is prepended to the itemid text. Not applicable if <code>itemid</code> set to false. 
	 * 
	 * @return Ambigous <string, number, mixed> active menu id if <code>itemid</code> not set, else return Itemid request variable or empty if not found.
	 */
	public static function get_active_menu_id($itemid = true, $url = null, $cat_request = false){
		
		$menu = JFactory::getApplication()->getMenu('site');
		$active = $menu->getActive();
		$menuid = 0;
		$catparam = '';
		
		if(empty($url)){
			
			if(!empty($active->id)){
				
				$menuid = $active->id;
			}
		
			if($menuid <= 0){
				
				$menuid = JRequest::getInt('Itemid', 0);
			}
		} else {
			
			if(!empty($active->id)){
					
				$menuitems = $menu->getItems('link', $url, false);
				
				if(!empty($menuitems)){
			
					foreach ($menuitems as $menuitem){
							
						if(!empty($menuitem->id) && $menuitem->id == $active->id){
			
							$menuid = $menuitem->id;
							break;
						}
					}
					
					if($menuid == 0 && !empty($menuitems[0]->id)){
						
						$menuid = $menuitems[0]->id;
					}
				}
			} else {
					
				$menuitem = $menu->getItems('link', $url, true);
					
				if(!empty($menuitem)){
			
					$menuid = $menuitem->id;
				}
			}
		}
		
		if( $itemid && $cat_request ){
			
			$catid = JRequest::getInt('catid', 0);
			$catparam = $catid > 0 ? '&catid='.$catid : '';
		}
		
		return $menuid > 0 ? ( $itemid ? $catparam.'&Itemid='.$menuid : $menuid ) : '';
	}
	
	/**
	 * Loads the comments from the installed/selected comment system. The comment system <code>type</code> should tell which comment system need to be used to load the comments from. The possible values are:<br><br>
	 * jcomment - JComments (id and title are required) <br>
	 * fbcomment - Facebook comment system (url is required)<br>
	 * disqus - Disqus comment system (id, title, identifier and url are required)<br>
	 * intensedebate - Intense Debate comment system (id, title, identifier and url are required)<br>
	 * jacomment - JAComment system (id and title are required)<br>
	 * jomcomment - JomComment (id is required)<br><br>
	 * Passing any other value will silently skips the code. In all the above cases, <code>type</code> and <code>app_name</code> are required parameters. 
	 * While <code>type</code> specifies the comment system to be used, <code>app_name</code> is the Joomla extension name (ex: com_appname) which is loading the comments for its content.
	 *  
	 * @param string $type comment system type
	 * @param string $app_name extension name
	 * @param int $id id of the content for which the comments are being loaded
	 * @param string $title title of the content
	 * @param string $url page url in case of facebook/disqus/intensedebate comment system.
	 * @param string $identifier disqus username in case of disqus/intensedebate comment system.
	 * @param object $item the item object for kommento
	 * 
	 * @return string the code to render the comments.
	 */
	public static function load_comments($type, $app_name, $id=0, $title='', $url = '', $identifier='', $item = null){
		
		switch ($type){
			
			case 'jcomment':
			
				$app = JFactory::getApplication();
				$path = JPATH_ROOT.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php';
				
				if (file_exists($path)) {
					
					require_once($path);
					return JComments::showComments($id, $app_name, $title);
				}
				break;

			case 'fbcomment':
				
				$lang = str_replace('-', '_', JFactory::getLanguage()->getTag());
				
				return '
					<div id="fb-root"></div>
					<script type="text/javascript">
						(function(d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0]; 
							if (d.getElementById(id)) return; 
							js = d.createElement(s); 
							js.id = id;
							js.src = "//connect.facebook.net/'.$lang.'/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); 
						}(document, "script", "facebook-jssdk"));
					</script>
					<div class="fb-comments" data-href="'.$url.'" data-num-posts="5" data-width="640"></div>';
				break;
				
			case 'disqus':
				
				return '
					<div id="disqus_thread"></div>
					<script type="text/javascript">
						var disqus_shortname = "'.$identifier.'";
// 						var disqus_developer = 1;
						var disqus_identifier = "'.$id.'";
						var disqus_url = "'.$url.'";
						var disqus_title = "'.$title.'";
						(function() {
							var dsq = document.createElement("script"); 
							dsq.type = "text/javascript"; 
							dsq.async = true;
							dsq.src = "http://" + disqus_shortname + ".disqus.com/embed.js";
							(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
						})();
					</script>
					<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
					<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>';
				
			case 'intensedebate':
				
				return '
					<script>
						var idcomments_acct = "'.$identifier.'";
						var idcomments_post_id = "'.$id.'";
						var idcomments_post_url = "'.$url.'";
					</script>
					<span id="IDCommentsPostTitle" style="display:none"></span>
					<script type="text/javascript" src="http://www.intensedebate.com/js/genericCommentWrapperV2.js"></script>';
				break;
			
			case 'jacomment':

				if(!JRequest::getInt('print') && file_exists(JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'jacomment.php') && file_exists(JPATH_SITE.DS.'plugins'.DS.'system'.DS.'jacomment.php')){
					
					$_jacCode = "#{jacomment(.*?) contentid=(.*?) option=(.*?) contenttitle=(.*?)}#i";
					$_jacCodeDisableid = "#{jacomment(\s)off.*}#i";
					$_jacCodeDisable = "#{jacomment(\s)off}#i";
					
					if(!preg_match($_jacCode, $title) && !preg_match($_jacCodeDisable, $title) && !preg_match($_jacCodeDisableid, $title)) {
						
						return '{jacomment contentid='.$id.' option='.$app_name.' contenttitle='.$title.'}';
					}
				}
				
				break;

			case 'jomcomment':
				
				$path = JPATH_PLUGINS.DS.'content'.DS.'jom_comment_bot.php';
				
				if(file_exists($path)) {
					
					include_once( $path );
					return jomcomment($id, $app_name);
				}
				
				break;
				
			case 'kommento':
				
				$api = JPATH_ROOT.DS.'components'.DS.'com_komento'.DS.'bootstrap.php';
				
				if(file_exists($api)){
					
					require_once $api;
					$item->text = $item->introtext = !empty($item->description) ? $item->description : '';
					return Komento::commentify( $app_name, $item );
				}
				
				break;
				
			case 'ccomment':
				
				$utils = JPATH_ROOT . '/components/com_comment/helpers/utils.php';
				
				if(file_exists($utils)) {
					
					JLoader::discover('ccommentHelper', JPATH_ROOT . '/components/com_comment/helpers');
					return ccommentHelperUtils::commentInit($app_name, $item);
				}
				
				break;
		}
	}
	
	/**
	 * Checks the reCaptcha request variables
	 *
	 * @param string $private_key reCaptcha private key
	 * @return true if entered captcha is correct, false otherwise.
	 */
	public static function verify_captcha($private_key){
	
		if(!function_exists('_recaptcha_qsencode')){
	
			require_once CJLIB_PATH.DS.'framework'.DS.'recaptchalib.php';
		}
	
		$recaptcha_challenge_field = JRequest::getVar('recaptcha_challenge_field', null, 'post', 'string');
		$recaptcha_response_field = JRequest::getVar('recaptcha_response_field', null, 'post', 'string');
	
		$resp = recaptcha_check_answer($private_key, $_SERVER['REMOTE_ADDR'], $recaptcha_challenge_field, $recaptcha_response_field);
	
		if(!$resp->is_valid){
	
			return false;
		}
	
		return true;
	}
	
	/**
	 * Gets the version check update from corejoomla servers. Returns associative array with values of <ul>
	 * <li>version - version on servers</li>
	 * <li>released - release date of the version on server</li>
	 * <li>changelog - for future use</li>
	 * <li>status - boolean true if the version is equal, false otherwise</li>
	 * <li>connect - true if corejoomla.com is connected with the function, false otherwise.</li></ul>
	 * 
	 * @param string $component component to check for update
	 * @param version $current_version current version of the component to compare
	 * 
	 * return array update status of the component 
	 */
	public static function get_component_update_check($component, $current_version){
		
		$url = 'http://www.corejoomla.com/extensions.xml';
		$data = '';
		$check = array();
		$check['connect'] = 0;
		$check['current_version'] = $current_version;
		
		//try to connect via cURL
		if(function_exists('curl_init') && function_exists('curl_exec')) {
			
			$ch = @curl_init();
		
			@curl_setopt($ch, CURLOPT_URL, $url);
			@curl_setopt($ch, CURLOPT_HEADER, 0);
			
			//http code is greater than or equal to 300 ->fail
			@curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			//timeout of 5s just in case
			@curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			$data = @curl_exec($ch);
			
			@curl_close($ch);
		}
		
		//try to connect via fsockopen
		if($data == '' && function_exists('fsockopen')) {
		
			$errno = 0;
			$errstr = '';
		
			//timeout handling: 5s for the socket and 5s for the stream = 10s
			$fsock = @fsockopen("www.corejoomla.com", 80, $errno, $errstr, 5);
		
			if ($fsock) {
				
				@fputs($fsock, "GET /extensions.xml HTTP/1.1\r\n");
				@fputs($fsock, "HOST: www.corejoomla.com\r\n");
				@fputs($fsock, "Connection: close\r\n\r\n");
		
				//force stream timeout...
				@stream_set_blocking($fsock, 1);
				@stream_set_timeout($fsock, 5);
		
				$get_info = false;
				
				while (!@feof($fsock)) {
					
					if ($get_info) {
						
						$data .= @fread($fsock, 8192);
					} else {
						
						if (@fgets($fsock, 8192) == "\r\n") {
							
							$get_info = true;
						}
					}
				}
				
				@fclose($fsock);

				//need to check data cause http error codes aren't supported here
				if(!strstr($data, '<corejoomla>')) {
					
					$data = '';
				}
			}
		}
		
		//try to connect via fopen
		if ($data == '' && function_exists('fopen') && ini_get('allow_url_fopen')) {
		
			//set socket timeout
			ini_set('default_socket_timeout', 5);
		
			$handle = @fopen ($url, 'r');
		
			//set stream timeout
			@stream_set_blocking($handle, 1);
			@stream_set_timeout($handle, 5);
		
			$data	= @fread($handle, 8192);
		
			@fclose($handle);
		}
		
		if( !empty($data) && strstr($data, '<corejoomla>') ) {
			
			$xml = new SimpleXMLElement($data);
			
			foreach($xml->extension as $extension){
				
				if($extension['name'] == $component && $extension['jversion'] == '1.7'){
					
					$check['version']		= $extension->version;
					$check['released']		= $extension->released;
					$check['changelog']		= $extension->changelog;
					$check['status']		= version_compare( $check['current_version'], $check['version'] );
					$check['connect']		= 1;
					
					break;
				}
			}
		} else {
			
			$check['version']		= 'N/A';
			$check['released']		= 'N/A';
			$check['changelog']		= 'N/A';
			$check['status']		= '0';
			$check['connect']		= 0;
		}
		
		return $check;		
	}
	
	public static function get_user_groups_tree($id, $name, $value){
		
		$groups = array();
		
		if(APP_VERSION != '1.5'){
			
			$db = JFactory::getDbo();
			$query = '
				select
					concat( repeat(\'..\', count(parent.id) - 1), node.title) as text, node.id as value
				from 
					#__usergroups as node, #__usergroups as parent
				where
					node.lft between parent.lft and parent.rgt
				group by
					node.id
				order by
					node.lft';
		
			$db->setQuery($query);
			$groups = $db->loadObjectList();
		}else{
				
			$acl	= JFactory::getACL();
			$groups	= $acl->get_group_children_tree( null, 'USERS', false );
		}
			 
		$attribs	= ' ';
		$attribs	.= 'size="'.count($groups).'"';
		$attribs	.= 'class="inputbox"';
		$attribs	.= 'multiple="multiple"';
			
		return JHTML::_('select.genericlist', $groups, $name, $attribs, 'value', 'text', $value, $id );
	}
	
	public static function get_jomsocial_groups($id, $name, $value){
		
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		
		$query = '
			select 
				id as value, name as text 
			from 
				#__community_groups g
			left join
				#__community_groups_members m on g.id = m.groupid
			where
				m.memberid='.$user->id.' and approved = 1';
		
		$db->setQuery($query);
		$groups = $db->loadObjectList();
		
		$attribs	= ' ';
		$attribs	.= 'size="10"';
		$attribs	.= 'class="inputbox"';
		$attribs	.= 'multiple="multiple"';
			
		return JHTML::_('select.genericlist', $groups, $name, $attribs, 'value', 'text', $value, $id );
	}
	
	public static function get_jomsocial_group_users($gid){
		
		$user = JFactory::getUser();
		$db = JFactory::getDbo();
		
		$query = 'select count(*) from #__community_groups_members where memberid = '.$user->id.' and group_id='.$gid.' and approved = 1';
		$db->setQuery($query);
		$count = $db->loadResult();
		
		if($count > 0){
			
			$query = '
				select 
					u.id, u.name, u.email
				from
					#__community_groups_members m
				left join
					#__users u on m.memberid = u.id
				where
					m.groupid = '.$gid;
			
			$db->setQuery($query);
			$users = $db->loadObjectList();
			
			return $users;
		}
		
		return false;
	}
	
	/**
	 * Function to get the login redirect url based on Joomla version.
	 * 
	 * @param string $redirect_url redirect url used after login
	 * @param string $itemid itemid
	 */
	public static function get_login_url($redirect_url, $itemid){
		
		return APP_VERSION == '1.5' 
				? JRoute::_("index.php?option=com_user&view=login".$itemid."&return=".$redirect_url) 
				: JRoute::_("index.php?option=com_users&view=login".$itemid."&return=".$redirect_url);
	}
	
	/**
	 * Function to get browser information. 
	 * Courtesy: 
	 * 	http://www.php.net/manual/en/function.get-browser.php#101125
	 * 	http://www.geekpedia.com/code47_Detect-operating-system-from-user-agent-string.html
	 */
	public static function get_browser(){
		
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
		
		$OSList = array(
				'Windows 3.11' => '(Win16)',
				'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',
				'Windows 98' => '(Windows 98)|(Win98)',
				'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
				'Windows 2000 Service Pack 1' => '(Windows NT 5.01)',
				'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
				'Windows Server 2003' => '(Windows NT 5.2)',
				'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
				'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
				'Windows 8' => '(Windows NT 6.2)|(Windows 8)',
				'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
				'Windows ME' => '(Windows ME)|(Windows 98; Win 9x 4.90 )',
				'Windows CE' => '(Windows CE)',
				'Mac OS X Kodiak (beta)' => '(Mac OS X beta)',
				'Mac OS X Cheetah' => '(Mac OS X 10.0)',
				'Mac OS X Puma' => '(Mac OS X 10.1)',
				'Mac OS X Jaguar' => '(Mac OS X 10.2)',
				'Mac OS X Panther' => '(Mac OS X 10.3)',
				'Mac OS X Tiger' => '(Mac OS X 10.4)',
				'Mac OS X Leopard' => '(Mac OS X 10.5)',
				'Mac OS X Snow Leopard' => '(Mac OS X 10.6)',
				'Mac OS X Lion' => '(Mac OS X 10.7)',
				'Mac OS X' => '(Mac OS X)',
				'Mac OS' => '(Mac_PowerPC)|(PowerPC)|(Macintosh)',
				'Open BSD' => '(OpenBSD)',
				'SunOS' => '(SunOS)',
				'Solaris 11' => '(Solaris\/11)|(Solaris11)',
				'Solaris 10' => '((Solaris\/10)|(Solaris10))',
				'Solaris 9' => '((Solaris\/9)|(Solaris9))',
				'CentOS' => '(CentOS)',
				'QNX' => '(QNX)',
				'UNIX' => '(UNIX)',
				'Ubuntu 12.10' => '(Ubuntu\/12.10)|(Ubuntu 12.10)',
				'Ubuntu 12.04 LTS' => '(Ubuntu\/12.04)|(Ubuntu 12.04)',
				'Ubuntu 11.10' => '(Ubuntu\/11.10)|(Ubuntu 11.10)',
				'Ubuntu 11.04' => '(Ubuntu\/11.04)|(Ubuntu 11.04)',
				'Ubuntu 10.10' => '(Ubuntu\/10.10)|(Ubuntu 10.10)',
				'Ubuntu 10.04 LTS' => '(Ubuntu\/10.04)|(Ubuntu 10.04)',
				'Ubuntu 9.10' => '(Ubuntu\/9.10)|(Ubuntu 9.10)',
				'Ubuntu 9.04' => '(Ubuntu\/9.04)|(Ubuntu 9.04)',
				'Ubuntu 8.10' => '(Ubuntu\/8.10)|(Ubuntu 8.10)',
				'Ubuntu 8.04 LTS' => '(Ubuntu\/8.04)|(Ubuntu 8.04)',
				'Ubuntu 6.06 LTS' => '(Ubuntu\/6.06)|(Ubuntu 6.06)',
				'Red Hat Linux' => '(Red Hat)',
				'Red Hat Enterprise Linux' => '(Red Hat Enterprise)',
				'Fedora 17' => '(Fedora\/17)|(Fedora 17)',
				'Fedora 16' => '(Fedora\/16)|(Fedora 16)',
				'Fedora 15' => '(Fedora\/15)|(Fedora 15)',
				'Fedora 14' => '(Fedora\/14)|(Fedora 14)',
				'Chromium OS' => '(ChromiumOS)',
				'Google Chrome OS' => '(ChromeOS)',
				'Linux' => '(Linux)|(X11)',
				'OpenBSD' => '(OpenBSD)',
				'FreeBSD' => '(FreeBSD)',
				'NetBSD' => '(NetBSD)',
				'Andriod' => '(Android)',
				'iPod' => '(iPod)',
				'iPhone' => '(iPhone)',
				'iPad' => '(iPad)',
				'OS/8' => '(OS\/8)|(OS8)',
				'Older DEC OS' => '(DEC)|(RSTS)|(RSTS\/E)',
				'WPS-8' => '(WPS-8)|(WPS8)',
				'BeOS' => '(BeOS)|(BeOS r5)',
				'BeIA' => '(BeIA)',
				'OS/2 2.0' => '(OS\/220)|(OS\/2 2.0)',
				'OS/2' => '(OS\/2)|(OS2)',
				'Search engine or robot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(msnbot)|(Ask Jeeves\/Teoma)|(ia_archiver)'
		);
		
		$agent = $_SERVER['HTTP_USER_AGENT'];
		
		foreach($OSList as $os=>$match){
			
			// Find a match
			if (preg_match('/'.$match.'/i', $agent)){

				$platform = $os;
				break;
			}
		}

		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
			
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		} elseif(preg_match('/Firefox/i',$u_agent)){
			
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		} elseif(preg_match('/Chrome/i',$u_agent)){
			
			$bname = 'Google Chrome';
			$ub = "Chrome";
		} elseif(preg_match('/Safari/i',$u_agent)){
			
			$bname = 'Apple Safari';
			$ub = "Safari";
		} elseif(preg_match('/Opera/i',$u_agent)){
			
			$bname = 'Opera';
			$ub = "Opera";
		} elseif(preg_match('/Netscape/i',$u_agent)) {
			
			$bname = 'Netscape';
			$ub = "Netscape";
		}
		 
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			
			// we have no matching number just continue
		}
		 
		// see how many we have
		$i = count($matches['browser']);
		
		if ($i != 1) {
			
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				
				$version= $matches['version'][0];
			} else {
				
				$version= $matches['version'][1];
			}
		} else {
			
			$version= $matches['version'][0];
		}
		 
		// check if we have a number
		if ($version==null || $version=="") {
			
			$version="?";
		}
		 
		return array(
				'userAgent' => $u_agent,
				'name'      => $bname,
				'version'   => $version,
				'platform'  => $platform,
				'pattern'    => $pattern
		);
	}
	
	/**
	 * Downloads the geolite database file from maxmind database source location.
	 * 
	 * GeoLite data created by MaxMind, available from http://www.maxmind.com
	 */
	public static function download_geoip_databases(){

		$path = JPATH_ROOT.DS.'media'.DS.'com_cjlib'.DS.'geoip'.DS;
		
		if(file_exists($path.'GeoLiteCity.dat')){
			
			$filemtime = filemtime($path.'GeoLiteCity.dat');
			
			if((time() - $filemtime) >= 15*86400){
			
				JFile::delete($path.'GeoLiteCity.dat');
			} else {
				
				return false;
			}
		}
		
		CJFunctions::download_file('http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz', $path, 'GeoLiteCity.dat.gz');
		CJFunctions::uncompress($path.'GeoLiteCity.dat.gz', $path.'GeoLiteCity.dat');
		
		return true;
	}
	
	/**
	 * Uncompresses a gzipped file to destination.
	 * 
	 * @param string $source source file path
	 * @param string $target destination file path
	 */
	public static function uncompress($source, $target) {
		
		$sfp = gzopen($source, "rb");
		$fp = fopen($target, "w");
	
		while ($string = gzread($sfp, 4096)) {
			fwrite($fp, $string, strlen($string));
		}
		
		gzclose($sfp);
		fclose($fp);
	}
	
	/**
	 * Downloads the file from remote server to specified location on local server. Uses regular file operations or cURL or sockets (whichever available first) to download the file.
	 * 
	 * @param url $source_url url of the source file
	 * @param string $target_folder target folder name, should end with DS 
	 * @param string $target_file target file name.
	 */
	public static function download_file($source_url, $target_folder, $target_file){
		
		if(file_exists($target_file)){
			
			JFile::delete($target_file);
		} else {
			
			JFolder::create($target_folder);
		}
		
		//try to connect via fopen
		if (function_exists('fopen') && ini_get('allow_url_fopen')) {
		
			//set socket timeout
			ini_set('default_socket_timeout', 5);

			$handle = fopen ($source_url, 'rb');
			
			if($handle){
				
				$download = fopen($target_folder.$target_file, "wb");
				
				if($download){
					
					while(!feof($handle)) {
						
						fwrite($download, fread($handle, 1024 * 8 ), 1024 * 8 );
					}
				}
			}
			
			if($handle){
				
				fclose($handle);
			}
		}
		
		//try to connect via cURL
		else if(function_exists('curl_init') && function_exists('curl_exec')) {
			
			$fh = fopen ($target_folder.$target_file, "w");
			
			$options = array(
					CURLOPT_FILE => $fh,
					CURLOPT_URL => $source_url,
					CURLOPT_TIMEOUT => 28800,
					CURLOPT_FAILONERROR => 1,
					CURLOPT_HEADER => 0,
					CURLOPT_TIMEOUT => 5
			);
			
			$ch = curl_init();
			curl_setopt_array($ch, $options);
			curl_exec($ch);
			curl_close($ch);
			fclose($fh);
		}
		
		//try to connect via fsockopen
		else if(function_exists('fsockopen') && $data == '') {

			$errno = 0;
			$errstr = '';
			
			$parts = JString::parse_url($source_url);
			$hostname = $parts['host'];
			unset($parts['scheme']);
			unset($parts['host']);
			$filename = CJFunctions::join_url($parts);
			
			//timeout handling: 5s for the socket and 5s for the stream = 10s
			$fsock = fsockopen($hostname, 80, $errno, $errstr, 5);
			
			if ($fsock) {
			
				fputs($fsock, 'GET '.$filename.' HTTP/1.1\r\n');
				fputs($fsock, 'HOST: '.$hostname.'\r\n');
				fputs($fsock, 'Connection: close\r\n\r\n');
			
				//force stream timeout...
				stream_set_blocking($fsock, 1);
				stream_set_timeout($fsock, 5);
			
				$get_info = false;
				$download = fopen($target_folder.$target_file, 'wb');
			
				while (!feof($fsock)) {
						
					if ($get_info) {
			
						fwrite($download, fread($handle, 1024 * 8 ), 1024 * 8 );
					} else {
			
						if (fgets($fsock, 8192) == '\r\n') {
								
							$get_info = true;
						}
					}
				}

				fclose($fsock);
			}
		}
	}
	
	private static function join_url($parts, $encode=true) {
		
		if ( $encode ){
			
			if ( isset( $parts['user'] ) ){
				
				$parts['user'] = rawurlencode( $parts['user'] );
			}
			
			if ( isset( $parts['pass'] ) ){
				
				$parts['pass'] = rawurlencode( $parts['pass'] );
			}
			
			if ( isset( $parts['host'] ) && !preg_match( '!^(\[[\da-f.:]+\]])|([\da-f.:]+)$!ui', $parts['host'] ) ){
				
				$parts['host'] = rawurlencode( $parts['host'] );
			}
			
			if ( !empty( $parts['path'] ) ){
				
				$parts['path'] = preg_replace( '!%2F!ui', '/', rawurlencode( $parts['path'] ) );
			}
			
			if ( isset( $parts['query'] ) ){
				
				$parts['query'] = rawurlencode( $parts['query'] );
			}
			
			if ( isset( $parts['fragment'] ) ){
				
				$parts['fragment'] = rawurlencode( $parts['fragment'] );
			}
		}

		$url = '';
		
		if ( !empty( $parts['scheme'] ) ){
			
			$url .= $parts['scheme'] . ':';
		}
		
		if ( isset( $parts['host'] ) ){
			
			$url .= '//';
			
			if ( isset( $parts['user'] ) ){
				
				$url .= $parts['user'];
				
				if ( isset( $parts['pass'] ) ){ 
					
					$url .= ':' . $parts['pass'];
				}
				
				$url .= '@';
			}
			
			if ( preg_match( '!^[\da-f]*:[\da-f.:]+$!ui', $parts['host'] ) ){
				
				$url .= '[' . $parts['host'] . ']'; // IPv6
			} else {
				
				$url .= $parts['host'];             // IPv4 or name
			}
			
			if ( isset( $parts['port'] ) ){
				
				$url .= ':' . $parts['port'];
			}
			
			if ( !empty( $parts['path'] ) && $parts['path'][0] != '/' ){
				
				$url .= '/';
			}
		}
		
		if ( !empty( $parts['path'] ) ){
			
			$url .= $parts['path'];
		}
		
		if ( isset( $parts['query'] ) ){
			
			$url .= '?' . $parts['query'];
		}
		
		if ( isset( $parts['fragment'] ) ){
			
			$url .= '#' . $parts['fragment'];
		}
		
		return $url;
	}
	
	public static function send_messages_from_queue($records = 60, $delay = 0, $simulated = true, $force_ids = array()){
		
		if($simulated){

			$cjconfig = CJLib::get_cjconfig();
			
			if($cjconfig['manual_cron'] == '1'){
			
				require_once CJLIB_PATH.DS.'framework'.DS.'virtualcron.php';
				
				$delay = $delay > 0 ? $delay : intval($cjconfig['cron_delay']);
				$vcron = new virtualcron($delay, 'virtualcron.txt');
				
				if (!$vcron->allowAction()){
					
					return false;
				}
			} else {
				
				return false;
			}
		}

		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		
		$from = $app->getCfg('mailfrom' );
		$fromname = $app->getCfg('fromname' );
		$message_ids = array();
		$sent = array();
		
		$query = $db->getQuery(true);
		$query
			->select('id, to_addr, cc_addr, bcc_addr, html, message_id, params')
			->from('#__corejoomla_messagequeue');
		
		if(!empty($force_ids)){
			
			$query->where('id in ('.implode(',', $force_ids).') and status = 0');
		} else {
			
			$query->where('status = 0');
		}
		
		$db->setQuery($query, 0, $records);
		$queue_items = array();
		
		try {
		
			$queue_items = $db->loadObjectList();
		} catch (Exception $e){
			
			return false;
		}

		if(!empty($queue_items)){
			
			foreach($queue_items as $item){
				
				$message_ids[] = $item->message_id;
			}
			
			$message_ids = array_unique($message_ids);
			
			$query = $db->getQuery(true);
			
			$query
				->select('id, asset_id, asset_name, subject, description, params')
				->from('#__corejoomla_messages')
				->where('id in ('.implode(',', $message_ids).')');
			
			$db->setQuery($query);
			$messages = array();
			
			try{
					
				$messages = $db->loadObjectList('id');
			} catch (Exception $e){
			
				return false;
			}
			
			if(!empty($messages)){
				
				$template_path = CJLIB_PATH.DS.'framework'.DS.'mail_templates'.DS;
				
				foreach ($messages as &$message){
					
					$params = json_decode($message->params);
					
					if(!empty($params->template) && JFile::exists($template_path.$params->template)){
						
						$template = file_get_contents($template_path.$params->template);
						
						if(!empty($params->placeholders)){
							
							foreach ($params->placeholders as $key=>$value){
								
								$template = str_replace($key, $value, $template);
							}
						}
						
						$message->description = str_replace('{description}', $message->description,  $template);
					}
				}
				
				$ids = array();
				
				foreach ($queue_items as $item){
					
					$ids[] = $item->id;
					
					if(!empty($messages[$item->message_id])){
						
						$description = $messages[$item->message_id]->description;

						if(!empty($item->params)){
							
							$params = json_decode($item->params);
							
							if(!empty($params->placeholders)){
								
								foreach ($params->placeholders as $key=>$value){
									
									$description = str_replace($key, $value, $description);
								}
							}
						}
						
						try{
							
							$return = CJFunctions::send_email(
									$from, $fromname, $item->to_addr, $messages[$item->message_id]->subject, $description, $item->html, $item->cc_addr, $item->bcc_addr);
							
							if($return === true){
								
								$sent[] = $item->id;
							}
						}catch (Exception $e){
							
							// Add logger
							$date = JFactory::getDate()->format('Y.m.d');
							JLog::addLogger(array('text_file' => 'com_cjlib.'.$date.'.log.php'), JLog::ALL, 'com_cjlib');
							JLog::add('Send Messages From Queue - Error: '.print_r($e, true), JLog::ERROR, 'com_cjlib');
						}
					}
				}
				
				if(!empty($ids)){
					
					$created = JFactory::getDate()->toSql();
					$query = $db->getQuery(true);
					
					$query
						->update($db->qn('#__corejoomla_messagequeue'))
						->set($db->qn('status').' = 1, processed = '.$db->q($created))
						->where('id in ('.implode(',', $ids).')');
					
					$db->setQuery($query);
					
					try{
					
						$db->execute();
					} catch (Exception $e){
						
						return false;
					}
				}
			}
		}
		
		return $sent;
	}
	
	/**
	 * This function will add paragraph tags around textual content of an HTML file, leaving the HTML itself intact.
	 * This function assumes that the HTML syntax is correct and that the '<' and '>' characters
	 * are not used in any of the values for any tag attributes. If these assumptions are not met, mass paragraph chaos may ensue. Be safe.
	 * 
	 * credits: http://stackoverflow.com/questions/5961217/how-do-i-surround-all-text-pieces-with-paragraph-tags
	 * 
	 * @param unknown_type $str
	 */
	public static function nl2p_html($str) {
	
		// If we find the end of an HTML header, assume that this is part of a standard HTML file. Cut off everything including the
		// end of the head and save it in our output string, then trim the head off of the input. This is mostly because we don't
		// want to surrount anything like the HTML title tag or any style or script code in paragraph tags.
		if(strpos($str,'</head>')!==false) {
			$out=substr($str,0,strpos($str,'</head>')+7);
			$str=substr($str,strpos($str,'</head>')+7);
		}
	
		// First, we explode the input string based on wherever we find HTML tags, which start with '<'
		$arr=explode('<',$str);
	
		// Next, we loop through the array that is broken into HTML tags and look for textual content, or
		// anything after the >
		for($i=0;$i<count($arr);$i++) {
			
			if(strlen(trim($arr[$i]))>0) {
				
				// Add the '<' back on since it became collateral damage in our explosion as well as the rest of the tag
				$html='<'.substr($arr[$i],0,strpos($arr[$i],'>')+1);
	
				// Take the portion of the string after the end of the tag and explode that by newline. Since this is after
				// the end of the HTML tag, this must be textual content.
				$sub_arr=explode("\n",substr($arr[$i],strpos($arr[$i],'>')+1));
	
				// Initialize the output string for this next loop
				$paragraph_text='';
	
				// Loop through this new array and add paragraph tags (<p>...</p>) around any element that isn't empty
				for($j=0;$j<count($sub_arr);$j++) {
					
					if(strlen(trim($sub_arr[$j]))>0)
						
						$paragraph_text.='<p>'.trim($sub_arr[$j]).'</p>';
				}
	
				// Put the text back onto the end of the HTML tag and put it in our output string
				$out.=$html.$paragraph_text;
			}
	
		}
	
		// Throw it back into our program
		return $out;
	}
	
	public static function get_supported_avatars(){
		
		return array(
				'NA' => JText::_('COM_CJLIB_NONE'),
				'cjblog' => JText::_('COM_CJLIB_EXTENSION_CJBLOG'),
				'gravatar' => JText::_('COM_CJLIB_EXTENSION_GRAVATAR'),
				'jomsocial' => JText::_('COM_CJLIB_EXTENSION_JOMSOCIAL'),
				'cb' => JText::_('COM_CJLIB_EXTENSION_COMMUNITY_BUILDER'),
				'kunena' => JText::_('COM_CJLIB_EXTENSION_KUNENA'),
				'aup' => JText::_('COM_CJLIB_EXTENSION_ALPHA_USERPOINTS'),
				'touch' => JText::_('COM_CJLIB_EXTENSION_MIGHTY_TOUCH')
			);
	}
	
	public static function get_supported_streams(){
	
		return array(
				'NA' => JText::_('COM_CJLIB_NONE'),
				'jomsocial' => JText::_('COM_CJLIB_EXTENSION_JOMSOCIAL'),
				'touch' => JText::_('COM_CJLIB_EXTENSION_MIGHTY_TOUCH')
		);
	}
	
	public static function get_supported_point_systems(){
	
		return array(
				'NA' => JText::_('COM_CJLIB_NONE'),
				'cjblog' => JText::_('COM_CJLIB_EXTENSION_CJBLOG'),
				'jomsocial' => JText::_('COM_CJLIB_EXTENSION_JOMSOCIAL'),
				'aup' => JText::_('COM_CJLIB_EXTENSION_ALPHA_USERPOINTS'),
				'touch' => JText::_('COM_CJLIB_EXTENSION_MIGHTY_TOUCH')
		);
	}
	
	public static function get_available_email_templates(){
		
		$templates = array();
		$path = CJLIB_PATH.DS.'framework'.DS.'mail_templates';
		
		if(file_exists($path)){
		
			$templates = JFolder::files($path, '*.tpl');
		}
		
		return $templates;
	}
	
	public static function get_supported_editors(){
	
		return array(
				'default' => JText::_('COM_CJLIB_WYSIWYG_EDITOR'),
				'bbcode' => JText::_('COM_CJLIB_BBCODE_EDITOR')
		);
	}
	
	public static function store_rating($asset_id, $item_id, $rating, $user_id = 0, $action_id = 0){
		
		$db = JFactory::getDBO();
		
		if($user_id > 0){
			
			$query = 'select count(*) from '.T_CJ_RATING_DETAILS.' where item_id = '.$item_id.' and created_by = '.$user_id.' and asset_id = 1';
			$db->setQuery($query);
			$count = $db->loadResult();
			
			if($count > 0){
				
				return -1;
			}
		}
		
		$createdate = JFactory::getDate()->toSql();
		
		$query = '
				insert into
					#__corejoomla_rating_details(asset_id, item_id, action_id, rating, created_by, created)
				values
					('.$asset_id.','.$item_id .','.$action_id.','.$rating.','.$user_id.','.$db->quote($createdate).')';
		
		$db->setQuery($query);
			
		if($db->query() && $db->getAffectedRows() > 0){
		
			$query = '
				insert into
					#__corejoomla_rating(item_id, asset_id, total_ratings, sum_rating, rating)
				values
					('.$item_id.','.$asset_id.', total_ratings + 1, sum_rating + '.$rating.', sum_rating / total_ratings)
				on duplicate key update
					total_ratings = total_ratings + 1,
					sum_rating = sum_rating + '.$rating.',
					rating = sum_rating / total_ratings';
		
			$db->setQuery($query);
			
			if($db->query()){
				
				return true;
			}
		}
		
		return false;
	}
	
	public static function get_rating($asset_id, $item_id){
		
		$db = JFactory::getDbo();
		
		$query = '
			select 
				total_ratings, sum_rating, rating 
			from 
				#__corejoomla_rating 
			where 
				item_id = '.$item_id.' and asset_id = '.$asset_id;
		
		$db->setQuery($query, 0, 1);
		$rating = $db->loadAssoc();
		
		$rating['total_ratings'] = !empty($rating['total_ratings']) ? $rating['total_ratings'] : '0';
		$rating['sum_rating'] = !empty($rating['sum_rating']) ? $rating['sum_rating'] : '0';
		$rating['rating'] = !empty($rating['rating']) ? $rating['rating'] : '0';
		
		return $rating;
	}
	
	public static function quoteName($name, $db){
		
		return APP_VERSION == '1.5' ? $db->nameQuote($name) : $db->quoteName($name);
	}
	
	public static function get_categories_table_markup($categories, $options = array()){
		
		$max_columns = isset($options['max_columns']) ? $options['max_columns'] : 3;
		$max_children = isset($options['max_children']) ? $options['max_children'] : 0;
		$base_url = isset($options['base_url']) ? $options['base_url'] : '';
		$menu_id = isset($options['menu_id']) ? $options['menu_id'] : '';
		$attribs = isset($options['link_attribs']) ? $options['link_attribs'] : array();
		$stat_primary = isset($options['stat_primary']) ? $options['stat_primary'] : null;
		$stat_secondary = isset($options['stat_secondary']) ? $options['stat_secondary'] : null;
		$stat_tooltip = isset($options['stat_tooltip']) ? $options['stat_tooltip'] : null;
		
		$num_rows = ceil(count($categories) / $max_columns);
		$table = '<div class="row-fluid">';
		$colspan = 'span'.(12/$max_columns);
		$itemid = 0;
		
		foreach($categories as $category){
			
			if($itemid % $num_rows == 0) $table = $table.'<div class="'.$colspan.'">';
			
			$url = JRoute::_($base_url.'&id='.$category['id'].':'.$category['alias'].$menu_id);
			$title = CJFunctions::escape($category['title']);
			
			if(!empty($stat_primary) && !empty($stat_secondary)){
				
				$title = $title.' <span class="muted">('.$category[$stat_primary].'/'.$category[$stat_secondary].')</span>';
				$attribs['title'] = !empty($stat_tooltip) ? JText::sprintf($stat_tooltip, $category['title'], $category[$stat_primary], $category[$stat_secondary]) : '';
				$attribs['class'] = !empty($attribs['class']) ? $attribs['class'].' tooltip-hover' : 'tooltip-hover';
			} else if(!empty($stat_primary)){
				
				$title = $title.' <span class="muted">('.$category[$stat_primary].')</span>';
				$attribs['title'] = !empty($stat_tooltip) ? JText::sprintf($stat_tooltip, $category['title'], $category[$stat_primary]) : '';
				$attribs['class'] = !empty($attribs['class']) ? $attribs['class'].' tooltip-hover' : 'tooltip-hover';
			}
			
			$table = $table.'<ul class="unstyled no-space-left">';
			$table = $table.'<li class="parent-item">'.JHtml::link($url, $title, $attribs).'</li>';
			
			if($max_children > 0 && count($category['children']) > 0){
				
				$child_count = 0;
				
				foreach($category['children'] as $child){
					
					$url = JRoute::_($base_url.'&id='.$child['id'].':'.$child['alias'].$menu_id);
					$title = CJFunctions::escape($child['title']);
					
					if(!empty($stat_primary) && !empty($stat_secondary)){
						
						$title = $title.' ('.$child[$stat_primary].' / '.$child[$stat_secondary].')';
						$attribs['title'] = !empty($stat_tooltip) ? JText::sprintf($stat_tooltip, $child['title'], $stat_primary, $stat_secondary) : '';
						$attribs['class'] = !empty($attribs['class']) ? $attribs['class'].' tooltip-hover' : 'tooltip-hover';
					} else if(!empty($stat_primary)){
						
						$title = $title.' ('.$child[$stat_primary].')';
						$attribs['title'] = !empty($stat_tooltip) ? JText::sprintf($stat_tooltip, $child['title'], $stat_primary) : '';
						$attribs['class'] = !empty($attribs['class']) ? $attribs['class'].' tooltip-hover' : 'tooltip-hover';
					}
					
					$table = $table.'<li class="child-item">'.JHtml::link($url, $title, $attribs).'</li>';
					
					if($child_count + 1 == $max_children) break;
					
					$child_count++;
				}
			}
			
			$table = $table.'</ul>';
			
			if(($itemid % $num_rows == $num_rows - 1) || ($itemid + 1 == count($categories))) $table = $table.'</div>';
			
			$itemid++;
		}
		
		$table = $table.'</div>';
		
		return $table;
	}

	public static function get_joomla_categories_table_markup($categories, $options = array()){
	
		$max_columns = isset($options['max_columns']) ? $options['max_columns'] : 3;
		$max_children = isset($options['max_children']) ? $options['max_children'] : 0;
		$base_url = isset($options['base_url']) ? $options['base_url'] : '';
		$menu_id = isset($options['menu_id']) ? $options['menu_id'] : '';
		$attribs = isset($options['link_attribs']) ? $options['link_attribs'] : array();
		$stat_primary = isset($options['stat_primary']) ? $options['stat_primary'] : null;
		$stat_secondary = isset($options['stat_secondary']) ? $options['stat_secondary'] : null;
		$stat_tooltip = isset($options['stat_tooltip']) ? $options['stat_tooltip'] : null;
	
		$num_rows = ceil(count($categories) / $max_columns);
		$table = '<div class="row-fluid">';
		$colspan = 'span'.(12/$max_columns);
		$itemid = 0;
	
		foreach($categories as $category){
				
			if($itemid % $num_rows == 0) $table = $table.'<div class="'.$colspan.'">';
				
			$url = JRoute::_($base_url.'&id='.$category->id.':'.$category->alias.$menu_id);
			$title = CJFunctions::escape($category->title);
				
			if(!empty($stat_primary) && !empty($stat_secondary)){
	
				$title = $title.' <small><span class="muted">('.$category->$stat_primary.'/'.$category->$stat_secondary.')</span></small>';
				$attribs['title'] = !empty($stat_tooltip) ? JText::sprintf($stat_tooltip, $category->title, $category->$stat_primary, $category->$stat_secondary) : '';
				$attribs['class'] = !empty($attribs['class']) ? $attribs['class'].' tooltip-hover' : 'tooltip-hover';
			} else if(!empty($stat_primary)){
	
				$title = $title.' <small><span class="muted">('.$category->$stat_primary.')</span></small>';
				$attribs['title'] = !empty($stat_tooltip) ? JText::sprintf($stat_tooltip, $category->title, $category->$stat_primary) : '';
				$attribs['class'] = !empty($attribs['class']) ? $attribs['class'].' tooltip-hover' : 'tooltip-hover';
			}
				
			$table = $table.'<ul class="unstyled no-space-left">';
			$table = $table.'<li class="parent-item">'.JHtml::link($url, $title, $attribs).'</li>';
				
			if($max_children > 0 && count($category->children) > 0){
	
				$child_count = 0;
	
				foreach($category->children as $child){
						
					$url = JRoute::_($base_url.'&id='.$child['id'].':'.$child['alias'].$menu_id);
					$title = CJFunctions::escape($child['title']);
						
					if(!empty($stat_primary) && !empty($stat_secondary)){
	
						$title = $title.' ('.$child[$stat_primary].' / '.$child[$stat_secondary].')';
						$attribs['title'] = !empty($stat_tooltip) ? JText::sprintf($stat_tooltip, $child['title'], $stat_primary, $stat_secondary) : '';
						$attribs['class'] = !empty($attribs['class']) ? $attribs['class'].' tooltip-hover' : 'tooltip-hover';
					} else if(!empty($stat_primary)){
	
						$title = $title.' ('.$child[$stat_primary].')';
						$attribs['title'] = !empty($stat_tooltip) ? JText::sprintf($stat_tooltip, $child['title'], $stat_primary) : '';
						$attribs['class'] = !empty($attribs['class']) ? $attribs['class'].' tooltip-hover' : 'tooltip-hover';
					}
						
					$table = $table.'<li class="child-item">'.JHtml::link($url, $title, $attribs).'</li>';
						
					if($child_count + 1 == $max_children) break;
						
					$child_count++;
				}
			}
				
			$table = $table.'</ul>';
				
			if(($itemid % $num_rows == $num_rows - 1) || ($itemid + 1 == count($categories))) $table = $table.'</div>';
				
			$itemid++;
		}
	
		$table = $table.'</div>';
	
		return $table;
	}
	
	/**
	 * A private function that is used to initialize kunena app
	 * 
	 * @return boolean true if success, false if not compatible kunena installation found
	 */
	private static function _initialize_kunena(){
		
		if (!(class_exists('KunenaForum') && KunenaForum::isCompatible('2.0') && KunenaForum::installed())) {
			
			return false;
		}
		
		KunenaForum::setup();
		
		return true;
	}
	
	/**
	 * Gets the html markup for listing Joomla categories in a column list format, found in cjblog categories listing page
	 * 
	 * @param list $categories the categories flat list to be displayed
	 * @param JObject $params the params object of the component, should contain
	 * <pre>
	 *      <strong>exclude_categories:</strong> array of category ids to exclude from adding to table
	 *      <strong>max_category_columns:</strong> maximum number of columns displayed in table
	 *      <strong>show_cat_num_articles:</strong> if set, displays number of items in the category
	 *      <strong>show_base_description:</strong> if set, displays the description of the item
	 *      <strong>show_base_image:</strong> if category has params, displays image set in params 
	 *      <strong>max_category_subitems:</strong> number of child items need to be displayed below each category
	 * </pre> 
	 * 
	 * @param array $options list of options to alter behavior
	 * <pre>
	 *      <strong>class:</strong> the class name added to the container, default is category-table
	 *      <strong>base_url:</strong> the url should be used as base to build href for each category, id:alias is automatically added to it
	 *      <strong>itemid:</strong> the itemid to be added to the url before passing to JRoute
	 * </pre>
	 * @return string the final markup of the column formatted categories listing 
	 */
	public static function get_joomla_categories_table($categories, $params, $options){
		
		if(empty($categories)) return '';
		
		//************************** PARAMS *********************************//
		$class = isset($options['class']) ? $options['class'] : 'category-table';
		$base_url = $options['base_url'];
		$itemid = $options['itemid'];
		$categories_excluded = $params->get('exclude_categories', array());
		//************************** PARAMS *********************************//
		$count_of_excluded = 0;
		
		foreach ($categories as $category){
			
			if(in_array($category->id, $categories_excluded)){
				
				$count_of_excluded++;
			}
		}
		
		$content = '<div class="'.$class.'" id="'.$class.'"><div class="row-fluid">';
		$column_span = 12 / $params->get('max_category_columns', 3);
		$categories_per_column = ceil((count($categories) - $count_of_excluded) / $params->get('max_category_columns', 3));
		$num_subcategories = 0;
		$i = 0;
		
		foreach ($categories as $category){
			
			if(!in_array($category->id, $categories_excluded)){
				
				if($i % $categories_per_column == 0){
					
					$content = $content .'<div class="span'.$column_span.'">';
				}
				
				$content = $content . '<ul class="category"><li class="parent">';
				$content = $content . '<a href="'.JRoute::_($base_url.'&id='.$category->id.':'.$category->alias.$itemid).'">'.CJFunctions::escape($category->title).'</a>';
				
				if($params->get('show_cat_num_articles')){
					
					$content = $content . ' <span class="muted">('.$category->numitems.')</span>';
				}
				
				if($params->get('show_base_description')){
					
					$content = $content . '<div>'.$category->description.'</div>';
				}
				
				
				if($params->get('show_base_image')){
					
					$category_params = json_decode($category->params);
					
					if(!empty($category_params) && !empty($category_params->image)){
						
						$content = $content . '<img class="img-polaroid padbottom-5" src="'.$category_params->image.'"/>';
					}
				}
				
				$content = $content . '</li>';
				
				$children = $category->getChildren();
				
				if(!empty($children)){
					
					$num_subcategories = 1;
					
					foreach ($children as $child){
						
						if(!in_array($child->id, $categories_excluded)){
							
							$content = $content.'<li>';
							$content = $content.'<a href="'.JRoute::_($base_url.'&id='.$child->id.':'.$child->alias.$itemid).'">'.CJFunctions::escape($child->title).'</a>';
										
							if($params->get('show_cat_num_articles')){
								
								$content = $content.' <span class="muted">('.$child->numitems.')</span></li>';
							}
							
							$content = $content.'</li>';
						}
						
						if($num_subcategories == $params->get('max_category_subitems')){
							
							break;
						}
					}
				}
				
				$content = $content . '</ul>';
				
				if(($i % $categories_per_column == $categories_per_column - 1) || ($i+1 == count($categories))){
					
					$content = $content .'</div>';
				}
				
				$i++;
			} // end if the category not excluded
		} // end for
		
		$content = $content . '</div></div>';
		
		return $content;
	}
	
	/**
	 * Loads the language strings of the component in the order en-GB, en-GB.overrides, current languages, current language overrides
	 * @param string $component the component name for which language should be loaded
	 * @param string $site if set loads site language else loads admin languages 
	 */
	public static function load_component_language($component, $site = true){

		$location = $site ? JPATH_ROOT : JPATH_ADMINISTRATOR;
		
		//************************ Language loading *************************************/
		$user = JFactory::getUser();
		$jlang = JFactory::getLanguage();
		
		$jlang->load($component, $location, 'en-GB', true);
		$jlang->load($component.'.override', $location, 'en-GB', true);
		$jlang->load($component, $location, $jlang->getTag(), true);
		$jlang->load($component.'.override', $location, $jlang->getTag(), true);
		
		if(!$user->guest){
			
			jimport('joomla.registry.registry');
			
			$uparams = is_object($user->params) ? $user->params : new JRegistry($user->params);
			$userlang = '';
		
			if(APP_VERSION >= 3) {
				$userlang = $uparams->get('language', '');
			} else {
				$userlang = $uparams->getValue('language', '');
			}
		
			if(!empty($userlang)) {
		
				$jlang->load($component, $location, $userlang, true);
				$jlang->load($component.'.override', $location, $userlang, true);
			}
		}
		//************************ Language loading *************************************/
	}
	
	/**
	 * Checks if the countries table has the language specific country names added. If yes, returnes the language code otherwise returns *
	 * @return string language code if the coutry codes exist for the current user language else *
	 */
	public static function get_country_language(){
		
		$db = JFactory::getDbo();
		$language = JFactory::getLanguage();
		
		$query = $db->getQuery(true);
		$query
			->select('count(*)')
			->from('#__corejoomla_countries')
			->where('language = '.$db->quote($language->getTag()));
		
		$db->setQuery($query);
		$count = $db->loadResult();
		
		return $count > 0 ? $language->getTag() : '*';
	}
	
	/**
	 * Gets the list of countries filtered by the user language if available, otherwise gets countries of language *
	 * 
	 * @return Array List of countries 
	 */
	public static function get_country_names(){
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$language = CJFunctions::get_country_language();
		
		$query
			->select('country_code, country_name')
			->from('#__corejoomla_countries')
			->where('language = '.$db->q($language))
			->order('country_name');
		
		$db->setQuery($query);
		$countries = $db->loadObjectList('country_code');
		
		return !empty($countries) ? $countries : array();
	}
	
	public static function get_country_name($country_code){
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$language = CJFunctions::get_country_language();
		
		$query
			->select('country_name')
			->from('#__corejoomla_countries')
			->where('country_code = '.$db->q($country_code).' and language = '.$db->q($language));
		
		$db->setQuery($query);
		$country = $db->loadResult();
		
		return $country;
	}
	
	/**
	 * Gets the first image location in the html data provided, if the data contains img tags.
	 * 
	 * @param string $html the data where the search is performed
	 * @return string src attribute of the first img tag if found
	 */
	public static function get_first_image($html){
	
		preg_match_all('/<img .*src=["|\']([^"|\']+)/i', $html, $matches);
	
		foreach ($matches[1] as $key=>$value) {
				
			return $value;
		}
	
		return '';
	}
	
	public static function compare_string_dates($compare, $compare_to){
		
		$date1 = JFactory::getDate($compare);
		$date2 = JFactory::getDate($compare_to);
		
		return $date1 > $date2 ? 1 : ($date1 == $date2 ? 0 : -1);
	}
}
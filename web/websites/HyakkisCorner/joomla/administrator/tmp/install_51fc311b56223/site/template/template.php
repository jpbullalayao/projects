<?php
/**
 * @version		$Id: template.php 01 2012-04-01 11:37:09Z maverick $
 * @package		CoreJoomla.Template
 * @subpackage	Components.cjlib
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CJTemplateFramework {
	
	/**
	 * Gets the path to a template file from overrides path if exists, selected template path if selected, default template otherwise.
	 * @param string $component component name
	 * @param string $template theme name
	 * @param string $layout template file from theme
	 */
	public static function get_view_path($component, $template, $layout) {
		
		if(strlen($layout) <= 0)  return false;
		
		$file = $layout.'.php';
		$template_path = CJTemplateFramework::get_template_path($component, $template, $file);
		
		if(JFile::exists($template_path.$file)){
			
			return $template_path.$file;
		}else{
			
			return false;
		}
	}

	/**
	 * Gets the theme path. If overrides theme exists it will take first priority, otherwise selected theme and default theme takes precendence respectively.
	 * 
	 * @param string $component component name
	 * @param string $template theme name
	 * @param string $file template file in theme folder
	 */
	public static function get_template_path($component, $template, $file){
		
		$template_path = null;
		
		$component_path = JPATH_ROOT.DS.'components'.DS.$component.DS.'templates'.DS;
		$overrides_path = JPATH_ROOT.DS.'templates'.DS.'cjoverrides'.DS.$component.DS;
		
		if(strlen($template) <= 0) {
			
			$template_path =  $component_path.'default'.DS;
			
		}else if(JFile::exists( $overrides_path.$template.DS.$file ) ) {
			
			$template_path =  $overrides_path.DS.$template.DS;
		}else if(JFile::exists($component_path.$template.DS.$file)) {
			
			$template_path =  $component_path.$template.DS;
		}else {
			
			$template_path =  $component_path.'default'.DS;
		}

		return $template_path;
	}

	/**
	 * Gets the uri to the theme. If overrides theme exists it will take first priority, otherwise selected theme and default theme takes precendence respectively.
	 * 
	 * @param string $component component name
	 * @param string $template theme name
	 * @param string $file template file in theme folder
	 */
	public static function get_template_uri($component, $template, $file){
		
		$component_path = JPATH_ROOT.DS.'components'.DS.$component.DS.'templates'.DS;
		$overrides_path = JPATH_ROOT.DS.'templates'.DS.'cjoverrides'.DS.$component.DS;
		$component_uri = JURI::root(true).'/components/'.$component.'/templates/';
		$overrides_uri = JURI::root(true).'/templates/cjoverrides/'.$component.'/';
		
		$file = $file.'.php';
		$template_uri = '';
		
		if(strlen($template) <= 0) {
			
			$template_uri =  $component_uri.'default';
		}else if(JFile::exists($overrides_path.$template.DS.$file)) {
			
			$template_uri =  $overrides_uri.$template;
		}else if(JFile::exists($component_path.$template.DS.$file)) {
			
			$template_uri =  $component_uri.$template;
		}else {
			
			$template_uri =  $component_uri.'default';
		}

		return $template_uri;
	}

	/**
	 * Gets the template located in componentpath/assets/include/listing.tpl by substituting the passed variables. The default template content can be overriden by passing it as second parameter.
	 * 
	 * @param array $array ordered list of values to be replaced in the template
	 */
	public static function parse_listing_template($array, $tpl=null){
		
		if(null == $tpl) {
			
			if(!file_exists(JPATH_COMPONENT.DS.'assets'.DS.'include'.DS.'listing.tpl')) return false;
			
			$tpl = file_get_contents(JPATH_COMPONENT.DS.'assets'.DS.'include'.DS.'listing.tpl');
		}
		
		$search = preg_match_all('/{.*?}/', $tpl, $matches);

		for($i = 0; $i < $search; $i++){
			$matches[0][$i] = str_replace(array('{', '}'), null, $matches[0][$i]);
		}

		foreach($matches[0] as $value){
			
			$tpl = str_replace('{' . $value . '}', $array[$value], $tpl);
		}

		return $tpl;
	}
	
	/**
	 * Gets the html to display loaded modules, if any, in the position <code>position</code>
	 * 
	 * @param string $position module position to load
	 * @return string html to render the module position
	 */
	public static function load_module_position($position) {

		jimport( 'joomla.application.module.helper' );
		
		if(count(JModuleHelper::getModules($position))) {
        	
            $document	= JFactory::getDocument();
            $renderer	= $document->loadRenderer('modules');
            $options	= array('style' => 'xhtml');
            
            return $renderer->render($position, $options, null);
        }else {
        	
            return '';
        }
    }
}

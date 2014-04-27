<?php 
/**
 * @version		$Id: framework.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.CJLib
 * @subpackage	Components.framework
 * @copyright	Copyright (C) 2009 - 2010 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

defined("T_CJ_RATING") or define("T_CJ_RATING",								"#__corejoomla_rating");
defined("T_CJ_RATING_DETAILS") or define("T_CJ_RATING_DETAILS",				"#__corejoomla_rating_details");
defined("T_CJ_MESSAGES") or define("T_CJ_MESSAGES",							"#__corejoomla_messages");
defined("T_CJ_MESSAGEQUEUE") or define("T_CJ_MESSAGEQUEUE",					"#__corejoomla_messagequeue");

defined('DS') or define('DS', 												DIRECTORY_SEPARATOR);
defined('CJLIB_VER') or define('CJLIB_VER', 								'2.0.0');
defined('CJLIB_PATH') or define('CJLIB_PATH', 								JPATH_ROOT.DS.'components'.DS.'com_cjlib');
defined('CJLIB_URI') or define('CJLIB_URI', 								JURI::root(true).'/components/com_cjlib');

defined('APP_VERSION') or (
		version_compare(JVERSION, '3.0.0', 'ge') 
			? define('APP_VERSION', 3) 
			: (version_compare(JVERSION,'1.6.0','ge') ? define('APP_VERSION', 2.5) : define('APP_VERSION', 1.5)));

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filter.output');
jimport('joomla.utilities.date');
jimport('joomla.log.log');
jimport('joomla.mail.helper');
jimport('joomla.html.html.list');
jimport('joomla.html.html.access');
jimport('joomla.session.session');

require_once CJLIB_PATH.DS.'framework'.DS.'xssclean.php';

JFactory::getLanguage()->load('com_cjlib.sys', JPATH_ADMINISTRATOR);

class CJLib {
	
	static $_bootstrap_loaded = false;
	
	/**
	 * Imports required package to be used in applications
	 * 
	 * @param string $library ex: corejoomla.tree.nestedtree
	 */
	public static function import($package, $cjprefixed = false, $force = false, $custom_tag = false){
		
		$lib = null;
		$document = JFactory::getDocument();
		$app = JFactory::getApplication();
		
		switch ($package){
				
			case 'corejoomla.framework.core':
				
				$lib = CJLIB_PATH.DS.'framework'.DS.'functions.php';
				break;
				
			case 'corejoomla.template.core':
				
				$lib = CJLIB_PATH.DS.'template'.DS.'template.php';
				break;
				
			case 'corejoomla.nestedtree.core':
				
				$lib = CJLIB_PATH.DS.'tree'.DS.'nestedtree.php';
				break;
				
			case 'corejoomla.nestedtree.ui':

				$document->addStyleSheet(CJLIB_URI.'/tree/ui/css/jquery.mcdropdown.min.css');
				$document->addScript(CJLIB_URI.'/tree/ui/scripts/jquery.bgiframe.js');
				$document->addScript(CJLIB_URI.'/tree/ui/scripts/jquery.mcdropdown.min.js');
				break;
				
			case 'corejoomla.forms.form':
			
				$lib = CJLIB_PATH.DS.'forms'.DS.'form.php';
				break;
			
			case 'corejoomla.forms.image':
			
				$lib = CJLIB_PATH.DS.'forms'.DS.'image.php';
				break;
				
			case 'corejoomla.ui.bootstrap':
				
				$bsloaded = !empty($app->cjbootstrap) ? $app->cjbootstrap : false;
				
				if(!$bsloaded || $force){
					
					if(APP_VERSION < 3){
						
						$document->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
						
						if($cjprefixed){
							
							if(JFactory::getLanguage()->isRTL()){
								
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap.rtl.min.css', $custom_tag);
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap-responsive.rtl.min.css', $custom_tag);
							} else {
								
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap.min.css', $custom_tag);
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap-responsive.min.css', $custom_tag);
							}
						} else {
							
							if(JFactory::getLanguage()->isRTL()){
								
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/bootstrap.rtl.min.css', $custom_tag);
							} else {

								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/bootstrap.min.css', $custom_tag);
							}
							
							CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/bootstrap-responsive.min.css', $custom_tag);
						}

						CJFunctions::load_jquery(array('libs'=>array()));
						$document->addScript(CJLIB_URI.'/bootstrap/js/bootstrap.min.js');
					} else {
	
						JHtml::_('bootstrap.framework');
						JHtmlBootstrap::loadCss(true, $document->direction);
					}
					
					$app->cjbootstrap = true;
				}
				
				break;
				
			case 'corejoomla.ui.fa.bootstrap':
				
				$bsloaded = !empty($app->cjbootstrap) ? $app->cjbootstrap : false;
				
				if(!$bsloaded || $force){

					if(APP_VERSION < 3){
				
						$document->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
				
						if($cjprefixed){
								
							if(JFactory::getLanguage()->isRTL()){

								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap.fontawesome.rtl.min.css', $custom_tag);
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap-responsive.rtl.min.css', $custom_tag);
							} else {

								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap.fontawesome.min.css', $custom_tag);
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/cj.bootstrap-responsive.min.css', $custom_tag);
							}
						} else {
								
							if(JFactory::getLanguage()->isRTL()){
								
								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/bootstrap.fontawesome.rtl.min.css', $custom_tag);
							} else {

								CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/bootstrap.fontawesome.min.css', $custom_tag);
							}
							
							CJFunctions::add_css_to_document($document, CJLIB_URI.'/bootstrap/css/bootstrap-responsive.min.css', $custom_tag);
						}

						$document->addCustomTag('<!--[if IE 7]><link rel="stylesheet" href="'.CJLIB_URI.'/bootstrap/css/font-awesome-ie7.min.css"><![endif]-->');
				
						CJFunctions::load_jquery(array('libs'=>array()));
						$document->addScript(CJLIB_URI.'/bootstrap/js/bootstrap.min.js');
					} else {
				
						JHtml::_('bootstrap.framework');
						JHtmlBootstrap::loadCss(true, $document->direction);
						CJFunctions::load_jquery(array('libs'=>array('fontawesome'), 'custom_tag'=>$custom_tag));
					}

					$app->cjbootstrap = true;
					$app->jqueryplugins = !empty($app->jqueryplugins) ? $app->jqueryplugins : array();
					$app->jqueryplugins[] = 'fontawesome';
				}
				
				break;
				
			default:
				
				$lib = CJLIB_PATH.DS.'dummy.php';
		}
		
		if(JFile::exists($lib)) {
			
			require_once $lib;
		}
	}
	
	public static function get_cjconfig($rebuild = false){
		
		$app = JFactory::getApplication();
		$config = $app->getUserState( 'CJLIB_CONFIG' );
		
		if(empty($config) || $rebuild){
			
			$db = JFactory::getDbo();
			$config = array();
			
			$query = 'select config_name, config_value from #__cjlib_config';
			$db->setQuery($query);
			$params = $db->loadObjectList();
			
			if(!empty($params)){
				
				foreach($params as $param){
					
					$config[$param->config_name] = $param->config_value;
				}
			} else {
				
				$app = JApplication::getInstance('site');
				$router = $app->getRouter();

				$random = CJFunctions::generate_random_key(16);
					
		        $query = "
		        	insert into 
		        		#__cjlib_config (config_name, config_value) 
		        	values 
		        		('cron_secret', ".$db->quote($random)."),
		        		('manual_cron', 1),
		        		('cron_emails', 60),
		        		('cron_delay', 10) 
		        	on duplicate key 
		        		update config_value = values (config_value)";
		        
				$db->setQuery($query);
				$db->query();
				
				$query = 'select config_name, config_value from #__cjlib_config';
				$db->setQuery($query);
				$params = $db->loadObjectList();
							
				foreach($params as $param){
					
					$config[$param->config_name] = $param->config_value;
				}
			}
			
			$app->setUserState('CJLIB_CONFIG', $config);
		}
		
		return $config;
	}
}
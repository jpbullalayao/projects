<?php

/**
 * file containing administrator function for the jfusion plugin
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    JFusionPlugins
 * @subpackage SMF1
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Load the JFusion framework
 */
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusion' . DS . 'models' . DS . 'model.jfusion.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusion' . DS . 'models' . DS . 'model.abstractadmin.php';

/**
 * JFusion Admin Class for SMF 1.1.x
 * For detailed descriptions on these functions please check the model.abstractadmin.php
 *
 * @category   JFusion
 * @package    JFusionPlugins
 * @subpackage SMF1
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */

class JFusionAdmin_smf extends JFusionAdmin
{
    /**
     * returns the name of this JFusion plugin
     *
     * @return string name of current JFusion plugin
     */
    function getJname()
    {
        return 'smf';
    }

    /**
     * return table name
     *
     * @return string table name
     */
    function getTablename()
    {
        return 'members';
    }

    /**
     * setup plugin from path
     *
     * @param string $forumPath Source path user to find config files
     *
     * @return array
     */
    function setupFromPath($forumPath)
    {
        //check for trailing slash and generate file path
        if (substr($forumPath, -1) == DS) {
            $myfile = $forumPath . 'Settings.php';
        } else {
            $myfile = $forumPath . DS . 'Settings.php';
        }
        $params = array();
        //try to open the file
        if (($file_handle = @fopen($myfile, 'r')) === false) {
            JError::raiseWarning(500, JText::_('WIZARD_FAILURE') . ": $myfile " . JText::_('WIZARD_MANUAL'));
        } else {
            //parse the file line by line to get only the config variables
            $file_handle = fopen($myfile, 'r');
            $config = array();
            while (!feof($file_handle)) {
                $line = fgets($file_handle);
                if (strpos($line, '$') === 0) {
                    $vars = explode("'", $line);
                    if (isset($vars[1]) && isset($vars[0])) {
                        $name = trim($vars[0], ' $=');
                        $value = trim($vars[1], ' $=');
                        $config[$name] = $value;
                    }
                }
            }
            fclose($file_handle);
            $params['database_host'] = isset($config['db_server']) ? $config['db_server'] : '';
            $params['database_type'] = 'mysql';
            $params['database_name'] = isset($config['db_name']) ? $config['db_name'] : '';
            $params['database_user'] = isset($config['db_user']) ? $config['db_user'] : '';
            $params['database_password'] = isset($config['db_passwd']) ? $config['db_passwd'] : '';
            $params['database_prefix'] = isset($config['db_prefix']) ? $config['db_prefix'] : '';
            $params['source_url'] = isset($config['boardurl']) ? $config['boardurl'] : '';
            $params['cookie_name'] = isset($config['cookiename']) ? $config['cookiename'] : '';
            $params['source_path'] = $forumPath;
        }
        return $params;
    }

    /**
     * Returns the a list of users of the integrated software
     *
     * @param int $limitstart start at
     * @param int $limit number of results
     *
     * @return array
     */
    function getUserList($limitstart = 0, $limit = 0)
    {
        // initialise some objects
        $db = JFusionFactory::getDatabase($this->getJname());
        $query = 'SELECT memberName as username, emailAddress as email from #__members';
        $db->setQuery($query,$limitstart,$limit);
        $userlist = $db->loadObjectList();
        return $userlist;
    }

    /**
     * returns user count
     *
     * @return int user count
     */
    function getUserCount()
    {
        //getting the connection to the db
        $db = JFusionFactory::getDatabase($this->getJname());
        $query = 'SELECT count(*) from #__members';
        $db->setQuery($query);
        //getting the results
        return $db->loadResult();
    }

    /**
     * get default user group list
     *
     * @return array array with object with default user group list
     */
    function getUsergroupList()
    {
        //getting the connection to the db
        $db = JFusionFactory::getDatabase($this->getJname());
        $query = 'SELECT ID_GROUP as id, groupName as name FROM #__membergroups WHERE minPosts = -1';
        $db->setQuery($query);
        $usergrouplist = $db->loadObjectList();
        //append the default usergroup
        $default_group = new stdClass;
        $default_group->id = 0;
        $default_group->name = 'Default User';
        $usergrouplist[] = $default_group;
        return $usergrouplist;
    }

    /**
     * get default user group
     *
     * @return string object with default user group
     */
    function getDefaultUsergroup()
    {
        $params = JFusionFactory::getParams($this->getJname());
        $usergroups = JFusionFunction::getCorrectUserGroups($this->getJname(),null);
        $usergroup_id = 0;
        if(!empty($usergroups)) {
            $usergroup_id = $usergroups[0];
        }
        if ($usergroup_id==0) {
            return 'Default Usergroup';
        }
        //we want to output the usergroup name
        $db = JFusionFactory::getDatabase($this->getJname());
        $query = 'SELECT groupName FROM #__membergroups WHERE ID_GROUP = ' . (int)$usergroup_id;
        $db->setQuery($query);
        return $db->loadResult();
    }

    /**
     * return list of post groups
     *
     * @return object with default user group
     */
    function getUserpostgroupList()
    {
        //getting the connection to the db
        $db = JFusionFactory::getDatabase($this->getJname());
        $query = 'SELECT ID_GROUP as id, groupName as name FROM #__membergroups WHERE minPosts != -1';
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * function  return if user can register or not
     *
     * @return boolean true can register
     */
    function allowRegistration()
    {
        $db = JFusionFactory::getDatabase($this->getJname());
        $query = 'SELECT value FROM #__settings WHERE variable =\'registration_method\';';
        $db->setQuery($query);
        $new_registration = $db->loadResult();
        if ($new_registration == 3) {
            $result = false;
            return $result;
        } else {
            $result = true;
            return $result;
        }
    }

    /**
     * regenerate redirect code
     *
     * @param string $url
     * @param int $itemid
     *
     * @return string output php redirect code
     */
    function generateRedirectCode($url, $itemid)
    {
        //create the new redirection code
        /*
        $pattern = \'#action=(login|admin|profile|featuresettings|news|packages|detailedversion|serversettings|theme|manageboards|postsettings|managecalendar|managesearch|smileys|manageattachments|viewmembers|membergroups|permissions|regcenter|ban|maintain|reports|viewErrorLog|optimizetables|detailedversion|repairboards|boardrecount|convertutf8|helpadmin|packageget)#\';
        */
        $redirect_code = '
//JFUSION REDIRECT START
//SET SOME VARS
$joomla_url = \'' . $url . '\';
$joomla_itemid = ' . $itemid . ';
    ';
        $redirect_code.= '
if (!defined(\'_JEXEC\') && strpos($_SERVER[\'QUERY_STRING\'], \'dlattach\') === false && strpos($_SERVER[\'QUERY_STRING\'], \'verificationcode\') === false)';
        $redirect_code.= '
{
    $pattern = \'#action=(login|logout)#\';
    if ( !preg_match( $pattern , $_SERVER[\'QUERY_STRING\'] )) {
        $file = $_SERVER["SCRIPT_NAME"];
        $break = explode(\'/\', $file);
        $pfile = $break[count($break) - 1];
        $query = str_replace(\';\', \'&\', $_SERVER[\'QUERY_STRING\']);
        $jfusion_url = $joomla_url . \'index.php?option=com_jfusion&Itemid=\' . $joomla_itemid . \'&jfile=\'.$pfile. \'&\' . $query;
        header(\'Location: \' . $jfusion_url);
        exit;
    }
}
//JFUSION REDIRECT END';
        return $redirect_code;
    }

    /**
     * Disable redirect mod
     *
     * @return void
     */
    function enableRedirectMod()
    {
        $params = JFusionFactory::getParams($this->getJname());
        $joomla_params = JFusionFactory::getParams('joomla_int');
        $joomla_url = $joomla_params->get('source_url');
        $joomla_itemid = $params->get('redirect_itemid');

        //check to see if all vars are set
        if (empty($joomla_url)) {
            JError::raiseWarning(0, JText::_('MISSING') . ' Joomla URL');
        } else if (empty($joomla_itemid) || !is_numeric($joomla_itemid)) {
            JError::raiseWarning(0, JText::_('MISSING') . ' ItemID');
        } else if (!$this->isValidItemID($joomla_itemid)) {
            JError::raiseWarning(0, JText::_('MISSING') . ' ItemID '. JText::_('MUST BE'). ' ' . $this->getJname());
        } else {
            $error = $this->disableRedirectMod();
            $reason = '';
            $mod_file = $this->getModFile('index.php', $error, $reason);
            if ($error == 0) {
                //get the joomla path from the file
                jimport('joomla.filesystem.file');
                $file_data = JFile::read($mod_file);
                $redirect_code = $this->generateRedirectCode($joomla_url,$joomla_itemid);

                $search = '/\<\?php/si';
                $replace = '<?php' . $redirect_code;

                $file_data = preg_replace($search, $replace, $file_data);
                JFile::write($mod_file, $file_data);
            }
        }
    }

    /**
     * Disable redirect mod
     *
     * @return string
     */
    function disableRedirectMod()
    {
        $error = 0;
        $reason = '';
        $mod_file = $this->getModFile('index.php', $error, $reason);
        if ($error == 0) {
            //get the joomla path from the file
            jimport('joomla.filesystem.file');
            $file_data = JFile::read($mod_file);
            $search = '/(\r?\n)\/\/JFUSION REDIRECT START(.*)\/\/JFUSION REDIRECT END/si';
            preg_match_all($search, $file_data, $matches);
            //remove any old code
            if (!empty($matches[1][0])) {
                $file_data = preg_replace($search, '', $file_data);
                if (!JFile::write($mod_file, $file_data)) {
                    $error = 1;
                }
            }
        }
        return $error;
    }

    /**
     * Called by Framework user for displaying and configuring usergroups
     *
     * @param string $name         name of element
     * @param string $value        value of element
     * @param string $node         node
     * @param string $control_name name of controller
     *
     * @return string html
     */
    function showRedirectMod($name, $value, $node, $control_name)
    {
        $error = 0;
        $reason = '';
        $mod_file = $this->getModFile('index.php', $error, $reason);
        if ($error == 0) {
            //get the joomla path from the file
            jimport('joomla.filesystem.file');
            $file_data = JFile::read($mod_file);
            preg_match_all('/\/\/JFUSION REDIRECT START(.*)\/\/JFUSION REDIRECT END/ms', $file_data, $matches);
            //compare it with our joomla path
            if (empty($matches[1][0])) {
                $error = 1;
                $reason = JText::_('MOD_NOT_ENABLED');
            }
        }

        //add the javascript to enable buttons
        if ($error == 0) {
            //return success
            $text = JText::_('REDIRECTION_MOD') . ' ' . JText::_('ENABLED');
            $disable = JText::_('MOD_DISABLE');
            $update = JText::_('MOD_UPDATE');
            $output = <<<HTML
            <img src="components/com_jfusion/images/check_good_small.png">{$text}
            <a href="javascript:void(0);" onclick="return module('disableRedirectMod')">{$disable}</a>
            <a href="javascript:void(0);" onclick="return module('enableRedirectMod')">{$update}</a>
HTML;
            return $output;
        } else {
            $text = JText::_('REDIRECTION_MOD') . ' ' . JText::_('DISABLED') . ': ' . $reason;
            $enable = JText::_('MOD_ENABLE');
            $output = <<<HTML
            <img src="components/com_jfusion/images/check_bad_small.png">{$text}
            <a href="javascript:void(0);" onclick="return module('enableRedirectMod')">{$enable}</a>
HTML;
            return $output;
        }
    }

    /**
     * uninstall function is to disable verious mods
     *
     * @return array
     */
    function uninstall()
    {
    	$error = $this->disableRedirectMod();
    	if (!empty($error)) {
    	   $reason= JText::_('REDIRECT_MOD_UNINSTALL_FAILED');
    	   return array(false, $reason);
    	}

    	return array(true, '');
    }

    /**
     * do plugin support multi usergroups
     *
     * @return bool
     */
    function isMultiGroup()
	{
		return false;
	}

    /**
     * do plugin support multi usergroups
     *
     * @return string UNKNOWN or JNO or JYES or ??
     */
    function requireFileAccess()
	{
		return 'JNO';
	}
}

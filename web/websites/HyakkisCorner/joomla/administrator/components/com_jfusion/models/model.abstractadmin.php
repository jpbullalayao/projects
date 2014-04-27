<?php

/**
 * Abstract admin file
 *
 * PHP version 5
 *
 * @category  JFusion
 * @package   Models
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusion' . DS . 'defines.php';

/**
 * Abstract interface for all JFusion functions that are accessed through the Joomla administrator interface
 *
 * @category  JFusion
 * @package   Models
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */
class JFusionAdmin
{
    /**
     * returns the name of this JFusion plugin
     *
     * @return string name of current JFusion plugin
     */
    function getJname()
    {
        return '';
    }

    /**
     * Returns the a list of users of the integrated software
     * @param int $limitstart optional
     * @param int $limit optional
     * @return array List of usernames/emails
     */
    function getUserList($limitstart = 0, $limit = 0)
    {
        return array();
    }

    /**
     * Returns the the number of users in the integrated software. Allows for fast retrieval total number of users for the usersync
     *
     * @return integer Number of registered users
     */
    function getUserCount()
    {
        return 0;
    }

    /**
     * Returns the a list of usersgroups of the integrated software
     *
     * @return array List of usergroups
     */
    function getUsergroupList()
    {
        return array();
    }

    /**
     * Function used to display the default usergroup in the JFusion plugin overview
     *
     * @return string Default usergroup name
     */
    function getDefaultUsergroup()
    {
        return '';
    }

    /**
     * Checks if the software allows new users to register
     *
     * @return boolean True if new user registration is allowed, otherwise returns false
     */
    function allowRegistration()
    {
        return true;
    }

    /**
     * returns the name of user table of integrated software
     *
     * @return string table name
     */
    function getTablename()
    {
        return '';
    }

    /**
     * Function finds config file of integrated software and automatically configures the JFusion plugin
     *
     * @param string $softwarePath path to root of integrated software
     *
     * @return array array with ne newly found configuration
     */
    function setupFromPath($softwarePath)
    {
        return array();
    }

    /**
     * Function that checks if the plugin has a valid config
     *
     * @return array result of the config check
     */
    function checkConfig()
    {
        $status = array();
        $jname = $this->getJname();
        //for joomla_int check to see if the source_url does not equal the default
        $params = JFusionFactory::getParams($jname);
        if ($jname == 'joomla_int') {
            $source_url = $params->get('source_url');
            if (!empty($source_url)) {
                $status['config'] = 1;
                $status['message'] = JText::_('GOOD_CONFIG');
            } else {
                $status['config'] = 0;
                $status['message'] = JText::_('NOT_CONFIGURED');
            }
        } else {
            $db = JFusionFactory::getDatabase($jname);
            $jdb = JFactory::getDBO();
            if (JError::isError($db) || !$db || strpos($db->name, 'mysql') === FALSE) {
                $status['config'] = 0;
                $status['message'] = $jname.' '.JText::_('NO_DATABASE');
            } elseif (JError::isError($jdb) || !$jdb || strpos($jdb->name, 'mysql') === FALSE) {
                $status['config'] = 0;
                $status['message'] = $jname.' -> joomla_int '.JText::_('NO_DATABASE');
            } elseif (!$db->connected()) {
                $status['config'] = 0;
                $status['message'] = $jname.' '.JText::_('NO_DATABASE');
            } elseif (!$jdb->connected()) {
                $status['config'] = 0;
                $status['message'] = $jname.' -> joomla_int '. JText::_('NO_DATABASE');
            } else {
                //added check for missing files of copied plugins after upgrade
                $path = JFUSION_PLUGIN_PATH . DS . $jname . DS;
                if (!file_exists($path.'admin.php')) {
                    $status['config'] = 0;
                    $status['message'] = JText::_('NO_FILES').' admin.php';
                } else if (!file_exists($path.'user.php')) {
                    $status['config'] = 0;
                    $status['message'] = JText::_('NO_FILES').' user.php';
                } else {
                    $cookie_domain = $params->get('cookie_domain');
                    $jfc = JFusionFactory::getCookies();
                    list($url) = $jfc->getApiUrl($cookie_domain);
                    if ($url) {
                        require_once(JPATH_SITE.DS.'components'.DS.'com_jfusion'.DS.'jfusionapi.php');

                        $joomla_int = JFusionFactory::getParams('joomla_int');
                        $api = new JFusionAPI($url,$joomla_int->get('secret'));
                        if (!$api->ping()) {
                            list ($message) = $api->getError();
                            $status['config'] = 0;
                            $status['message'] = $api->url. ' ' .$message;
                            return $status;
                        }
                    }
                    $source_path = $params->get('source_path');
                    if ($source_path && (strpos($source_path, 'http://') === 0 || strpos($source_path, 'https://') === 0)) {
                        $status['config'] = 0;
                        $status['message'] = JText::_('ERROR_SOURCE_PATH'). ' : '.$source_path ;
                    } else {
                        //get the user table name
                        $tablename = $this->getTablename();
                        // lets check if the table exists, now using the Joomla API
                        $table_list = $db->getTableList();
                        $table_prefix = $db->getPrefix();
                        if (!is_array($table_list)) {
                            $status['config'] = 0;
                            $status['message'] = $table_prefix . $tablename . ': ' . JText::_('NO_TABLE');
                        } else {
                            if (array_search($table_prefix . $tablename, $table_list) === false) {
                                //do a final check for case insensitive windows servers
                                if (array_search(strtolower($table_prefix . $tablename), $table_list) === false) {
                                    $status['config'] = 0;
                                    $status['message'] = $table_prefix . $tablename . ': ' . JText::_('NO_TABLE');
                                } else {
                                    $status['config'] = 1;
                                    $status['message'] = JText::_('GOOD_CONFIG');
                                }
                            } else {
                                $status['config'] = 1;
                                $status['message'] = JText::_('GOOD_CONFIG');
                            }
                        }
                    }
                }
            }
        }
        return $status;
    }

    /**
     * Function that checks if the plugin has a valid config
     * jerror is used for output
     *
     * @return void
     */
    function debugConfig()
    {
        //get registration status
        $new_registration = $this->allowRegistration();
        $jname = $this->getJname();
        //get the data about the JFusion plugins
        $db = JFactory::getDBO();
        $query = 'SELECT * from #__jfusion WHERE name = ' . $db->Quote($jname);
        $db->setQuery($query);
        $plugin = $db->loadObject();
        //output a warning to the administrator if the allowRegistration setting is wrong
        if ($new_registration && $plugin->slave == 1) {
            JError::raiseNotice(0, $jname . ': ' . JText::_('DISABLE_REGISTRATION'));
        }
        if (!$new_registration && $plugin->master == 1) {
            JError::raiseNotice(0, $jname . ': ' . JText::_('ENABLE_REGISTRATION'));
        }
        //most dual login problems are due to incorrect cookie domain settings
        //therefore we should check it and output a warning if needed.
        $params = JFusionFactory::getParams($jname);

	    $cookie_domain = $params->get('cookie_domain',-1);
	    if ($cookie_domain!==-1) {
		    $cookie_domain = str_replace(array('http://', 'https://'), array('', ''), $cookie_domain);
		    $correct_domain = '';
		    $correct_array = explode('.', html_entity_decode($_SERVER['SERVER_NAME']));

		    //check for domain names with double extentions
		    if (isset($correct_array[count($correct_array) - 2]) && isset($correct_array[count($correct_array) - 1])) {
			    //domain array
			    $domain_array = array('com', 'net', 'org', 'co', 'me');
			    if (in_array($correct_array[count($correct_array) - 2], $domain_array)) {
				    $correct_domain = '.' . $correct_array[count($correct_array) - 3] . '.' . $correct_array[count($correct_array) - 2] . '.' . $correct_array[count($correct_array) - 1];
			    } else {
				    $correct_domain = '.' . $correct_array[count($correct_array) - 2] . '.' . $correct_array[count($correct_array) - 1];
			    }
			    if ($correct_domain != $cookie_domain && !$this->allowEmptyCookieDomain()) {
				    JError::raiseNotice(0, $jname . ': ' . JText::_('BEST_COOKIE_DOMAIN') . ' ' . $correct_domain);
			    }
		    }
	    }

	    //also check the cookie path as it can interfere with frameless
	    $cookie_path = $params->get('cookie_path',-1);
	    if ($cookie_path!==-1) {
		    if ($cookie_path != '/' && !$this->allowEmptyCookiePath()) {
			    JError::raiseNotice(0, $jname . ': ' . JText::_('BEST_COOKIE_PATH') . ' /');
		    }
	    }

        //check that master plugin does not have advanced group mode data stored
        $master = JFusionFunction::getMaster();
        if (!empty($master) && $master->name == $jname && JFusionFunction::isAdvancedUsergroupMode($jname)) {
            JError::raiseWarning(0, $jname . ': ' . JText::_('ADVANCED_GROUPMODE_ONLY_SUPPORTED_FORSLAVES'));
        }

        // allow additional checking of the configuration
        $this->debugConfigExtra();
    }

    /**
     * Function that determines if the empty cookie path is allowed
     *
     * @return bool
     */
    function allowEmptyCookiePath()
    {
        return false;
    }

    /**
     * Function that determines if the empty cookie domain is allowed
     *
     * @return bool
     */
    function allowEmptyCookieDomain()
    {
        return false;
    }

    /**
     * Function to implement any extra debug checks for plugins
     *
     * @return void
     */
    function debugConfigExtra()
    {
    }

    /**
     * Get an usergroup element
     *
     * @param string $name         name of element
     * @param string $value        value of element
     * @param string $node         node of element
     * @param string $control_name name of controller
     *
     * @return string html
     */
    function usergroup($name, $value, $node, $control_name)
    {
        $jname = $this->getJname();
        //get the master plugin to be throughout
        $master = JFusionFunction::getMaster();
        $advanced = 0;

        if(JFusionFunction::isJoomlaVersion('1.6')){
            // set output format options in 1.6 only
            JHTML::setFormatOptions(array('format.eol' => "", 'format.indent' => ""));
        }
        //detect is value is a serialized array
        if (substr($value, 0, 2) == 'a:') {
            $value = unserialize($value);
            //use advanced only if this plugin is not set as master
            if (!empty($master) && $master->name != $this->getJname()) {
                $advanced = 1;
            }
        }
        if (JFusionFunction::validPlugin($this->getJname())) {
            $usergroups = $this->getUsergroupList();
            if (!empty($usergroups)) {
                $simple_usergroup = '<table style="width:100%; border:0">';
                $simple_usergroup.= '<tr><td>' . JText::_('DEFAULT_USERGROUP') . '</td><td>' . JHTML::_('select.genericlist', $usergroups, $control_name . '[' . $name . ']', '', 'id', 'name', $value) . '</td></tr>';
                $simple_usergroup.= '</table>';
                //escape single quotes to prevent JS errors
                $simple_usergroup = str_replace("'", "\'", $simple_usergroup);
            } else {
                $simple_usergroup = '';
            }
        } else {
            return JText::_('SAVE_CONFIG_FIRST');
        }
        //check to see if current plugin is a slave
        $db = JFactory::getDBO();
        $query = 'SELECT slave FROM #__jfusion WHERE name = ' . $db->Quote($jname);
        $db->setQuery($query);
        $slave = $db->loadResult();
        $list_box = '<select onchange="usergroupSelect(this.selectedIndex);">';
        if ($advanced == 1) {
            $list_box.= '<option value="0">'.JText::_('SIMPLE').'</option>';
        } else {
            $list_box.= '<option value="0" selected="selected">'.JText::_('SIMPLE').'</option>';
        }
        if ($slave == 1 && !empty($master) && JFusionFunction::hasFeature($jname,'updateusergroup')) {
            //allow usergroup sync
            if ($advanced == 1) {
                $list_box.= '<option selected="selected" value="1">'.JText::_('ADVANCED').'</option>';
            } else {
                $list_box.= '<option value="1">'.JText::_('ADVANCED').'</option>';
            }
            //prepare the advanced options
            $JFusionMaster = JFusionFactory::getAdmin($master->name);
            $master_usergroups = $JFusionMaster->getUsergroupList();
            $advanced_usergroup = '<table class="usergroups">';
            if ($advanced == 1) {
                foreach ($master_usergroups as $master_usergroup) {
                    $select_value = (!isset($value[$master_usergroup->id])) ? '' : $value[$master_usergroup->id];
                    $advanced_usergroup.= '<tr><td>' . $master_usergroup->name . '</td>';
                    $advanced_usergroup.= '<td>' . JHTML::_('select.genericlist', $usergroups, $control_name . '[' . $name . '][' . $master_usergroup->id . ']', '', 'id', 'name', $select_value) . '</td></tr>';
                }
            } else {
                foreach ($master_usergroups as $master_usergroup) {
                    $advanced_usergroup.= '<tr><td>' . $master_usergroup->name . '</td>';
                    $advanced_usergroup.= '<td>' . JHTML::_('select.genericlist', $usergroups, $control_name . '[' . $name . '][' . $master_usergroup->id . ']', '', 'id', 'name', '') . '</td></tr>';
                }
            }
            $advanced_usergroup.= '</table>';
            //escape single quotes to prevent JS errors
            $advanced_usergroup = str_replace("'", "\'", $advanced_usergroup);
        } else {
            $advanced_usergroup = '';
        }
        $list_box.= '</select>';

        $document = JFactory::getDocument();

        $js = <<<JS
        function usergroupSelect(option)
        {
            var myArray = [];
            myArray[0] = '{$simple_usergroup}';
            myArray[1] = '{$advanced_usergroup}';

            $('JFusionUsergroup').innerHTML = myArray[option];
        }
JS;
        $document->addScriptDeclaration($js);

        $return = '<table><tr><td>'.JText::_('USERGROUP'). ' '. JText::_('MODE').'</td><td>'.$list_box.'</td></tr><tr><td COLSPAN=2><div id="JFusionUsergroup">';
        if ($advanced == 1) {
            $return .= $advanced_usergroup;
        } else {
            $return .= $simple_usergroup;
        }
        $return .= '</div></td></tr></table>';
        return $return;
    }

    /**
     * Get an multiusergroup element
     *
     * @param string $name         name of element
     * @param string $value        value of element
     * @param string $node         node of element
     * @param string $control_name name of controller
     *
     * @return string html
     */
    function multiusergroup($name, $value, $node, $control_name)
    {
        $jname = $this->getJname();
        //get the master plugin to be throughout
        $master = JFusionFunction::getMaster();
        $advanced = 0;

        if(JFusionFunction::isJoomlaVersion('1.6')){
            // set output format options in 1.6 only
            JHTML::setFormatOptions(array('format.eol' => '', 'format.indent' => ''));
        }
        //detect is value is a serialized array
        if (substr($value, 0, 2) == 'a:') {
            $value = unserialize($value);
            //use advanced only if this plugin is not set as master
            if (!empty($master) && $master->name != $this->getJname()) {
                $advanced = 1;
            }
        }
        if (JFusionFunction::validPlugin($this->getJname())) {
            $usergroups = $this->getUsergroupList();
            if (!empty($usergroups)) {
                $simple_usergroup = '<table style="width:100%; border:0">';
                $simple_usergroup.= '<tr><td>' . JText::_('DEFAULT_USERGROUP') . '</td><td>' . JHTML::_('select.genericlist', $usergroups, $control_name . '[' . $name . ']', '', 'id', 'name', $value) . '</td></tr>';
                $simple_usergroup.= '</table>';
                //escape single quotes to prevent JS errors
                $simple_usergroup = str_replace("'", "\'", $simple_usergroup);
            } else {
                $simple_usergroup = '';
            }
        } else {
            return JText::_('SAVE_CONFIG_FIRST');
        }

        $params = JFusionFactory::getParams($jname);
        $multiusergroupdefault = $params->get('multiusergroupdefault');
        $master_usergroups = array();
        $JFusionMaster = null;
        if ( !empty($master) ) {
            $JFusionMaster = JFusionFactory::getAdmin($master->name);
            $master_usergroups = $JFusionMaster->getUsergroupList();
        }

        //check to see if current plugin is a slave
        $db = JFactory::getDBO();
        $query = 'SELECT slave FROM #__jfusion WHERE name = ' . $db->Quote($jname);
        $db->setQuery($query);
        $slave = $db->loadResult();
        $list_box = '<select onchange="usergroupSelect(this.selectedIndex);">';
        if ($advanced == 1) {
            $list_box.= '<option value="0">'.JText::_('SIMPLE').'</option>';
        } else {
            $list_box.= '<option value="0" selected="selected">'.JText::_('SIMPLE').'</option>';
        }

        $jfGroupCount = 0;
        if ($slave == 1 && $JFusionMaster && JFusionFunction::hasFeature($jname,'updateusergroup')) {
            //allow usergroup sync
            if ($advanced == 1) {
                $list_box.= '<option selected="selected" value="1">'.JText::_('ADVANCED').'</option>';
            } else {
                $list_box.= '<option value="1">'.JText::_('ADVANCED').'</option>';
            }
            //prepare the advanced options
            $advanced_usergroup = '<table id="usergroups" class="usergroups">';
            $advanced_usergroup.= '<tr><td>'.$JFusionMaster->getJname().'</td><td>'.$this->getJname().'</td><td>Default Group</td><td></td></tr>';

            $master_control_name = $control_name . '[' . $name . ']['.$JFusionMaster->getJname().']';
            $this_control_name = $control_name . '[' . $name . ']['.$this->getJname().']';
            if ($advanced == 1) {
                if ( isset($value[$JFusionMaster->getJname()]) && isset($value[$this->getJname()]) && count($value[$JFusionMaster->getJname()]) < count($value[$this->getJname()])) {
                    $groups = isset($value[$this->getJname()]) ? $value[$this->getJname()] : array();
                } else {
                    $groups = isset($value[$JFusionMaster->getJname()]) ? $value[$JFusionMaster->getJname()] : array();
                }

                foreach ($groups as $key => $select_value) {
                    $jfGroupCount++;
                    $select_value =  isset($value[$JFusionMaster->getJname()][$key]) ? $value[$JFusionMaster->getJname()][$key] : array();
                    $advanced_usergroup.= '<tr id="usergroups_row'.$jfGroupCount.'">';
                    if ($JFusionMaster->isMultiGroup()) {
                        $advanced_usergroup.= '<td>' . JHTML::_('select.genericlist', $master_usergroups , $master_control_name.'['.$jfGroupCount.'][]', 'MULTIPLE SIZE="10"', 'id', 'name', $select_value) . '</td>';
                    } else {
                        $advanced_usergroup.= '<td>' . JHTML::_('select.genericlist', $master_usergroups , $master_control_name.'['.$jfGroupCount.'][]', '', 'id', 'name', $select_value) . '</td>';
                    }

                    $select_value = isset($value[$this->getJname()][$key]) ? $value[$this->getJname()][$key] : array();
                    if ($this->isMultiGroup()) {
                        $advanced_usergroup.= '<td>' . JHTML::_('select.genericlist', $usergroups, $this_control_name.'['.$jfGroupCount.'][]', 'MULTIPLE SIZE="10"', 'id', 'name', $select_value) . '</td>';
                    } else {
                        $advanced_usergroup.= '<td>' . JHTML::_('select.genericlist', $usergroups, $this_control_name.'['.$jfGroupCount.'][]', '', 'id', 'name', $select_value) . '</td>';
                    }

                    $checked = '';
                    if ($multiusergroupdefault == $key) {
                        $checked = 'checked';
                    }
                    $advanced_usergroup.= '<td><input type="radio" '.$checked.' name="'.$control_name . '[' . $name . 'default]" value="'.$jfGroupCount.'"></td>';
                    $advanced_usergroup.= '<td><a href="javascript:removeRow('.$jfGroupCount.')">'. JText::_('REMOVE').'</a></td>';

                    $advanced_usergroup.= '</tr>';
                }
            } else {
                $jfGroupCount++;
                $select_value = '';
                $advanced_usergroup.= '<tr id="usergroups_row'.$jfGroupCount.'">';
                if ($JFusionMaster->isMultiGroup()) {
                    $advanced_usergroup.= '<td>' . JHTML::_('select.genericlist', $master_usergroups , $master_control_name.'['.$jfGroupCount.'][]', 'MULTIPLE SIZE="10"', 'id', 'name', $select_value) . '</td>';
                } else {
                    $advanced_usergroup.= '<td>' . JHTML::_('select.genericlist', $master_usergroups , $master_control_name.'['.$jfGroupCount.'][]', '', 'id', 'name', $select_value) . '</td>';
                }

                $select_value = '';
                if ($this->isMultiGroup()) {
                    $advanced_usergroup.= '<td>' . JHTML::_('select.genericlist', $usergroups, $this_control_name.'['.$jfGroupCount.'][]', 'MULTIPLE SIZE="10"', 'id', 'name', $select_value) . '</td>';
                } else {
                    $advanced_usergroup.= '<td>' . JHTML::_('select.genericlist', $usergroups, $this_control_name.'['.$jfGroupCount.'][]', '', 'id', 'name', $select_value) . '</td>';
                }
                $checked = 'checked';
                $advanced_usergroup.= '<td><input type="radio" '.$checked.' name="'.$control_name . '[' . $name . 'default]" value="'.$jfGroupCount.'"></td>';
                $advanced_usergroup.= '<td><a href="javascript:removeRow('.$jfGroupCount.')">'.JText::_('REMOVE').'</a></td>';

                $advanced_usergroup.= '</tr>';
            }

            $advanced_usergroup.= '</table>';
            //escape single quotes to prevent JS errors
            $advanced_usergroup = str_replace("'", "\'", $advanced_usergroup);
        } else {
            $advanced_usergroup = '';
        }
        $list_box.= '</select>';
        $plugin = array();
        if ($JFusionMaster) {
            $plugin['name'] = $this->getJname();
            $plugin['master'] = $JFusionMaster->getJname();
            $plugin['count'] = $jfGroupCount;
            $plugin[$this->getJname()]['groups'] = $usergroups;
            $plugin[$this->getJname()]['type'] = $this->isMultiGroup() ? 'multi':'single';
            $plugin[$JFusionMaster->getJname()]['groups'] = $master_usergroups;
            $plugin[$JFusionMaster->getJname()]['type'] = $JFusionMaster->isMultiGroup() ? 'multi':'single';
        }

        $document = JFactory::getDocument();
        $plugin = json_encode($plugin);
        $js = <<<JS
			var jfPlugin = {$plugin};

	        function usergroupSelect(option)
	        {
	            var myArray = [];
	            myArray[0] = '{$simple_usergroup}';
	            myArray[1] = '{$advanced_usergroup}';
	            $('JFusionUsergroup').innerHTML = myArray[option];

	            var addgroupset = $('addgroupset');
	            if (option == 1) {
	            	addgroupset.style.display = 'block';
	            } else {
	            	addgroupset.style.display = 'none';
	            }
	        }

	        function addRow() {
	        	jfPlugin['count']++;
	        	var count = jfPlugin['count'];

	        	var master = jfPlugin['master'];
	        	var name = jfPlugin['name'];

	        	var elTrNew = document.createElement('tr');
	        	elTrNew.id = 'usergroups_row'+count;

	        	var elTdmaster = document.createElement('td');
	        	elTdmaster.appendChild(createSelect(master));

	        	var elTdjname = document.createElement('td');
	        	elTdjname.appendChild(createSelect(name));

	        	var elInputNew = document.createElement('input');
	        	elInputNew.type = 'radio';
	        	elInputNew.name = 'params[multiusergroupdefault]';
				elInputNew.value = count;

	        	var elTddefault = document.createElement('td');
	        	elTddefault.appendChild(elInputNew);

	        	var elANew = document.createElement('a');
	        	elANew.href = 'javascript:removeRow('+count+')';
	        	elANew.innerHTML = 'Remove';

	        	var elTdremove = document.createElement('td');
	        	elTdremove.appendChild(elANew);

	        	elTrNew.appendChild(elTdmaster);
	        	elTrNew.appendChild(elTdjname);
	        	elTrNew.appendChild(elTddefault);
	        	elTrNew.appendChild(elTdremove);

	        	var divEls = $('usergroups');
	        	divEls.appendChild(elTrNew);
	        }

	        function removeRow(row) {
	        	var trEl = $("usergroups_row"+row);
	        	trEl.style.display = 'none';
	        	trEl.innerHTML = '';
	        }

	        function createSelect(name) {
	        	var count = jfPlugin['count'];
	        	var type = jfPlugin[name]['type'];
	        	var groups = jfPlugin[name]['groups'];

				var elSelNew = document.createElement('select');
				if (type == 'multi') {
					elSelNew.size=10;
					elSelNew.multiple='multiple';
				}
				elSelNew.name='params[multiusergroup]['+name+']['+count+'][]';
				var x;
				for (x in groups) {
					if (groups.hasOwnProperty(x)) {
						var elOptNew = document.createElement('option');
						elOptNew.text = groups[x].name;
						elOptNew.value = groups[x].id;
						elSelNew.appendChild(elOptNew);
					}
				}
				return elSelNew;
	        }
JS;
        $document->addScriptDeclaration($js);

        $addbutton='<a id="addgroupset"></a>';
        $return = '<table><tr><td>'.JText::_('USERGROUP'). ' '. JText::_('MODE').'</td><td>'.$list_box.'</td></tr><tr><td colspan="2"><div id="JFusionUsergroup">';
        if ($advanced == 1) {
            if (($JFusionMaster && $JFusionMaster->isMultiGroup()) || $this->isMultiGroup()) {
                $addbutton = '<a id="addgroupset" href="javascript:addRow()">'. JText::_('ADD_GROUP_PAIR').'</a>';
            }
            $return .= $advanced_usergroup;
        } else {
            if (($JFusionMaster && $JFusionMaster->isMultiGroup()) || $this->isMultiGroup()) {
                $addbutton = '<a id="addgroupset" style="display: none;" href="javascript:addRow()">'. JText::_('ADD_GROUP_PAIR').'</a>';
            }
            $return .= $simple_usergroup;
        }
        $return .= '</div>'.$addbutton.'</td></tr></table>';
        return $return;
    }

    /**
     * Function returns the path to the modfile
     *
     * @param string $filename file name
     * @param int    &$error   error number
     * @param string &$reason  error reason
     *
     * @return string $mod_file path and file of the modfile.
     */
    function getModFile($filename, &$error, &$reason)
    {
        //check to see if a path is defined
        $params = JFusionFactory::getParams($this->getJname());
        $path = $params->get('source_path');
        if (empty($path)) {
            $error = 1;
            $reason = JText::_('SET_PATH_FIRST');
        }
        //check for trailing slash and generate file path
        if (substr($path, -1) == DS) {
            $mod_file = $path . $filename;
        } else {
            $mod_file = $path . DS . $filename;
        }
        //see if the file exists
        if (!file_exists($mod_file) && $error == 0) {
            $error = 1;
            $reason = JText::_('NO_FILE_FOUND');
        }
        return $mod_file;
    }

    /**
     * Called when JFusion is uninstalled so that plugins can run uninstall processes such as removing auth mods
     * @return array    [0] boolean true if successful uninstall
     *                  [1] mixed reason(s) why uninstall was unsuccessful
     */
    function uninstall()
    {
        return array(true, '');
    }

    /**
     * do plugin support multi usergroups
     *
     * @return bool
     */
    function isMultiGroup()
    {
        static $muiltisupport;
        if (!isset($muiltisupport)) {
            $params = JFusionFactory::getParams($this->getJname());
            $multiusergroup = $params->get('multiusergroup',null);
            if ($multiusergroup !== null) {
                $muiltisupport = true;
            } else {
                $muiltisupport = false;
            }
        }
        return $muiltisupport;
    }

    /**
     * @return string UNKNOWN or JNO or JYES or ??
     */
    function requireFileAccess()
    {
        return 'UNKNOWN';
    }

	/**
	 * @return bool do the plugin support multi instance
	 */
	function multiInstance()
	{
		return true;
	}

    /**
     * Function to check if a given itemid is configured for the plugin in question.
     *
     * @param int $itemid
     * @return bool
     */
    function isValidItemID($itemid)
    {
        $result = false;
        if ($itemid) {
            $app = JFactory::getApplication();
            $menus = $app->getMenu('site');
            $item = $menus->getItem($itemid);
            if ($item) {
                $jPluginParam = unserialize(base64_decode($item->params->get('JFusionPluginParam')));
                if (is_array($jPluginParam) && $jPluginParam['jfusionplugin'] == $this->getJname()) {
                    $result = true;
                }
            }
        }
        return $result;
    }
}
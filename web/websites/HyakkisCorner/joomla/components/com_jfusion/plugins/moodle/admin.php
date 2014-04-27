<?php

/**
 * file containing administrator function for the jfusion plugin
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    JFusionPlugins
 * @subpackage Moodle
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * JFusion Admin Class for Moodle 1.8+
 * For detailed descriptions on these functions please check the model.abstractadmin.php
 *
 * @category   JFusion
 * @package    JFusionPlugins
 * @subpackage Moodle
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */

class JFusionAdmin_moodle extends JFusionAdmin
{
    /**
     * returns the name of this JFusion plugin
     * @return string name of current JFusion plugin
     */
    function getJname(){
        return 'moodle';
    }

    /**
     * @return string
     */
    function getTablename() {
        return 'user';
    }

    /**
     * @param string $forumPath
     * @return array
     */
    function setupFromPath($forumPath) {
        //check for trailing slash and generate file path
        if (substr($forumPath, -1) == DS) {
            $myfile = $forumPath . 'config.php';
        } else {
            $myfile = $forumPath . DS . 'config.php';
        }
        $params = array();
        if (($file_handle = @fopen($myfile, 'r')) === false) {
            JError::raiseWarning(500, JText::_('WIZARD_FAILURE') . ": $myfile " . JText::_('WIZARD_MANUAL'));
        } else {
            //parse the file line by line to get only the config variables
        	$CFG = new stdClass();
            $file_handle = fopen($myfile, 'r');
            while (!feof($file_handle)) {
                $line = trim(fgets($file_handle));
                 if (strpos($line, '$CFG->') !== false && strpos($line, ';') != 0) {
                    eval($line);
                }
            }
            fclose($file_handle);
            //save the parameters into array
            $params['database_host'] = $CFG->dbhost;
            $params['database_name'] = $CFG->dbname;
            $params['database_user'] = $CFG->dbuser;
            $params['database_password'] = $CFG->dbpass;
            $params['database_prefix'] = $CFG->prefix;
            $params['database_type'] = $CFG->dbtype;
            if (!empty($CFG->passwordsaltmain)) {
                $params['passwordsaltmain'] = $CFG->passwordsaltmain;
            }
            for ($i = 1;$i <= 20;$i++) { //20 alternative salts should be enough, right?
                $alt = 'passwordsaltalt' . $i;
                if (!empty($CFG->$alt)) {
                    $params[$alt] = $CFG->$alt;
                }
            }
            $params['source_path'] = $forumPath;
            if (substr($CFG->wwwroot, -1) == '/') {
                $params['source_url'] = $CFG->wwwroot;
            } else {
                //no slashes found, we need to add one
                $params['source_url'] = $CFG->wwwroot . '/';
            }
            $params['usergroup'] = '7'; #make sure we do not assign roles with more capabilities automatically
            //return the parameters so it can be saved permanently
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
        //getting the connection to the db
        $db = JFusionFactory::getDatabase($this->getJname());
        $query = 'SELECT username, email from #__user';
        $db->setQuery($query,$limitstart,$limit);

        //getting the results
        $userlist = $db->loadObjectList();
        return $userlist;
    }

    /**
     * @return int
     */
    function getUserCount() {
        //getting the connection to the db
        $db = JFusionFactory::getDatabase($this->getJname());
        $query = 'SELECT count(*) from #__user';
        $db->setQuery($query);
        //getting the results
        $no_users = $db->loadResult();
        return $no_users;
    }
    /**
     * @return array
     */
    function getUsergroupList() {
        //get the connection to the db
        $db = JFusionFactory::getDatabase($this->getJname());
        $query = 'SELECT id, name from #__role;';
        $db->setQuery($query);
        //getting the results
        return $db->loadObjectList();
    }
    /**
     * @return string
     */
    function getDefaultUsergroup() {
        $params = JFusionFactory::getParams($this->getJname());
        $usergroups = JFusionFunction::getCorrectUserGroups($this->getJname(),null);
        $usergroup_id = null;
        if(!empty($usergroups)) {
            $usergroup_id = $usergroups[0];
        }
        //we want to output the usergroup name
        $db = JFusionFactory::getDatabase($this->getJname());
        $query = 'SELECT name from #__role WHERE id = ' . (int)$usergroup_id;
        $db->setQuery($query);
        return $db->loadResult();
    }

    /**
     * @return bool
     */
    function allowRegistration() {
        $db = JFusionFactory::getDatabase($this->getJname());
        $query = 'SELECT value FROM #__config WHERE name = \'auth\' and value != \'jfusion\'';
        $db->setQuery($query);
        $auths = $db->loadResult();
        if (empty($auths)) {
            $result = false;
            return $result;
        } else {
            $result = true;
            return $result;
        }
    }

    /**
     * @return bool
     */
    function allowEmptyCookiePath() {
        return true;
    }

    /**
     * @return bool
     */
    function allowEmptyCookieDomain() {
        return true;
    }

    /**
     * @return mixed|string
     */
    public function moduleInstallation() {
        $jname = $this->getJname ();
        $params = JFusionFactory::getParams ( $jname );

        $db = JFusionFactory::getDatabase ( $jname );
        if (! JError::isError ( $db ) && ! empty ( $db )) {

            $source_path = $params->get ( 'source_path', '' );
            if (! file_exists ( $source_path . DS . 'admin' . DS . 'auth.php' )) {
                return JText::_ ( 'MOODLE_CONFIG_SOURCE_PATH' );
            }

            $mod_exists = false;
            if (file_exists ( $source_path . DS . 'auth' . DS . 'jfusion' . DS . 'auth.php' )) {
                $mod_exists = true;
            }

            if ($mod_exists) {
                $src = 'components/com_jfusion/images/tick.png';
                $mod = 'uninstallModule';
                $text = JText::_ ( 'MODULE_UNINSTALL_BUTTON' );
            } else {
                $src = 'components/com_jfusion/images/cross.png';
                $mod = 'installModule';
                $text = JText::_ ( 'MODULE_INSTALL_BUTTON' );
            }

            $html = <<<HTML
                <div class="button2-left">
                    <div class="blank">
                        <a href="javascript:void(0);" onclick="return module('{$mod}');">{$text}</a>
                    </div>
                </div>

                <img src="{$src}" style="margin-left:10px;" id="usergroups_img"/>
HTML;
            return $html;
        } else {
            return JText::_ ( 'MOODLE_CONFIG_FIRST' );
        }
    }

    /**
     * @return array
     */
    public function installModule() {

        $jname =  $this->getJname ();
        $db = JFusionFactory::getDatabase($jname);
        $params = JFusionFactory::getParams ( $jname );
        $source_path = $params->get ( 'source_path' );
        jimport ( 'joomla.filesystem.archive' );
        jimport ( 'joomla.filesystem.file' );
	    $pear_path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jfusion'.DS.'models'.DS. 'pear';
        require_once $pear_path.DS.'PEAR.php';
        $pear_archive_path = $pear_path.DS.archive_tar.DS.'Archive_Tar.php';
        require_once $pear_archive_path;

        $status = array('error' => array(),'debug' => array());
        $archive_filename = 'moodle_module_jfusion.tar.gz';
        $old_chdir = getcwd();
        $src_archive =  $src_path = realpath ( dirname ( __FILE__ ) ) . DS . 'install_module';
        $src_code =  $src_archive . DS . 'source';
        $dest = $source_path;

        // Create an archive to facilitate the installation into the Moodle installation while extracting
        chdir($src_code);
        $tar = new Archive_Tar( $archive_filename, 'gz' );
        $tar->setErrorHandling(PEAR_ERROR_PRINT);
        $tar->createModify( 'auth lang' , '', '' );
        chdir($old_chdir);

        $ret = JArchive::extract ( $src_code . DS . $archive_filename, $dest );
        JFile::delete($src_code . DS . $archive_filename);

        if ($ret) {
            $joomla = JFusionFactory::getParams('joomla_int');
            $joomla_baseurl = $joomla->get('source_url');
            $joomla_source_path = JPATH_ROOT.DS;

            // now set all relevant parameters in Moodles database
            // do not yet activate!

            $querys = array();
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_enabled\', value = \'0\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_ismaster\', value = \'0\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_fullpath\', value = '.$db->quote($joomla_source_path);
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_baseurl\', value = '.$db->quote($joomla_baseurl);
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_loginpath\', value = \'\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_logoutpath\', value = \'\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_formid\', value = \'login\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_relpath\', value = \'0\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_cookiedomain\', value = \'\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_cookiepath\', value = \'\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_username_id\', value = \'\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_password_id\', value = \'\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_cookie_secure\', value = \'0\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_cookie_httponly\', value = \'0\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_verifyhost\', value = \'0\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_leavealone\', value = \'\'';
            $querys[] = 'REPLACE INTO #__config_plugins SET plugin = \'auth/jfusion\' , name = \'jf_expires\', value = \'1800\'';
            foreach($querys as $query){
                $db->setQuery($query);
                $db->query();
                if ($db->getErrorNum() != 0) {
                    $status['error'] = $db->stderr ();
                    return $status;
                }
            }
            $status['error'] = $jname . ': ' . JText::sprintf('INSTALL_MODULE_ERROR', $src_archive, $dest);
        } else {
            $status['message'] = $jname .': ' . JText::_('INSTALL_MODULE_SUCCESS');
        }

        return $status;
    }

    /**
     * @return array
     */
    public function uninstallModule() {

        $status = array('error' => array(),'debug' => array());
        jimport ( 'joomla.filesystem.file' );
        jimport ( 'joomla.filesystem.folder' );

        $jname =  $this->getJname ();
        $db = JFusionFactory::getDatabase($jname);
        $params = JFusionFactory::getParams ( $jname );
        $source_path = $params->get ( 'source_path' );
        $xmlfile = realpath ( dirname ( __FILE__ ) ) . DS . 'install_module' . DS . 'source' . DS . 'listfiles.xml';

	    $listfiles = JFusionFunction::getXml($xmlfile);
        $files = $listfiles->getElementByPath('file');

        /**
         * @ignore
         * @var $file JSimpleXMLElement
         */
        foreach($files as $file){
            $file = $file->data();
            $file = preg_replace('#/#', DS, $file);
            @chmod($source_path . DS . $file, 0777);
            if(!is_dir($source_path . DS . $file)){
                JFile::delete($source_path . DS . $file);
            }else{
                JFolder::delete($source_path . DS . $file);
            }
        }
        $querys = array();
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_enabled\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_ismaster\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_fullpath\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_baseurl\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_loginpath\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_logoutpath\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_formid\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_relpath\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_cookiedomain\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_cookiepath\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_username_id\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_password_id\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_cookie_secure\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_cookie_httponly\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_verifyhost\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_leavealone\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_expires\'';
        $querys[] = 'DELETE FROM #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_expires\'';
        foreach($querys as $query){
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum() != 0) {
                $status['error'] = $db->stderr ();
                return $status;
            }
        }

        $status['message'] = $jname .': ' . JText::_('UNINSTALL_MODULE_SUCCESS');

        // remove jfusion as active plugin
        $query = 'SELECT value from #__config WHERE name = \'auth\'';
        $db->setQuery($query);
        $value = $db->loadResult();
        $auths = explode(',',$value);
        $key = array_search('jfusion',$auths);
        if ($key !== false){
            $authstr = $auths[0];
            for ($i=1; $i <= (count($auths)-1);$i++){
                if ($auths[$i] != 'jfusion'){
                    $authstr .= ','.$auths[$i];
                }
            }

        }
        return $status;
    }

    /**
     * @return mixed|string
     */
    public function moduleActivation() {
        $jname =  $this->getJname ();
        $params = JFusionFactory::getParams ( $jname );
        $db = JFusionFactory::getDatabase($jname);

        $source_path = $params->get ( 'source_path' );
        $jfusion_auth = $source_path . DS .'auth'. DS .'jfusion'. DS .'auth.php';
        if(file_exists($jfusion_auth)){
            // find out if jfusion is listed in the active auth plugins
            $query = 'SELECT value from #__config WHERE name = \'auth\'';
            $db->setQuery($query);
            $value = $db->loadResult();
            if (stripos($value,'jfusion')!== false ){
                // now find out if we have enabled the plugin
                $query = 'SELECT value from #__config_plugins WHERE plugin = \'auth/jfusion\' AND name = \'jf_enabled\'';
                $db->setQuery($query);
                $value = $db->loadResult();
                if  ($value == '1'){
                    $activated = 1;
                } else {
                    $activated = 0 ;
                }
            } else {
                $activated = 0;
            }

            if ($activated) {
                $src = 'components/com_jfusion/images/tick.png';
                $text = JText::_ ( 'MODULE_DEACTIVATION_BUTTON' );
            } else {
                $src = 'components/com_jfusion/images/cross.png';
                $text = JText::_ ( 'MODULE_ACTIVATION_BUTTON' );
            }

            $html = <<<HTML
			    <div class="button2-left">
			        <div class="blank">
			            <a href="javascript:void(0);"  onclick="return module('activateModule');">{$text}</a>
			        </div>
			    </div>
			    <input type="hidden" name="activation" id="activation" value="{$activated}"/>

			    <img src="{$src}" style="margin-left:10px;"/>
HTML;
        } else {
            $html = JText::_ ( 'MOODLE_CONFIG_FIRST' );
        }
        return $html;
    }

    /**
     * @return array|bool
     */
    public function activateModule(){
        $jname =  $this->getJname ();
        $params = JFusionFactory::getParams ( $jname );
        $db = JFusionFactory::getDatabase($jname);

        $activation = ((JRequest::getVar('activation', 1))?'true':'false');
        if ($activation == 'true') {
            $query = 'UPDATE #__config_plugins SET value = \'1\' WHERE plugin = \'auth/jfusion\' AND name = \'jf_enabled\'';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum() != 0) {
                $status['error'] = $db->stderr ();
                return $status;
            }
            // add jfusion plugin jfusion as active plugin
            $query = 'SELECT value from #__config WHERE name = \'auth\'';
            $db->setQuery($query);

            $value = $db->loadResult();
            $auths = explode(',',$value);

            $key = array_search('jfusion',$auths);

            if ($key !== false){ // already enabled ?!
                $status['error'] = 'key already enabled?';
                return $status;
            }
            $value .= ',jfusion';
            $query = 'UPDATE #__config SET value = \''.$value.'\' WHERE name = \'auth\'';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum() != 0) {
                $status['error'] = $db->stderr ();
                return $status;
            }
        } else {
            $query = 'UPDATE #__config_plugins SET value = \'0\' WHERE plugin = \'auth/jfusion\' AND name = \'jf_enabled\'';
            $db->setQuery($query);
            $db->query();
            if ($db->getErrorNum() != 0) {
                $status['error'] = $db->stderr ();
                return $status;
            }
            // remove jfusion as active plugin
            $query = 'SELECT value from #__config WHERE name = \'auth\'';
            $db->setQuery($query);
            $value = $db->loadResult();
            $auths = explode(',',$value);
            $key = array_search('jfusion',$auths);
            if ($key !== false){
                $authstr = $auths[0];
                for ($i=1; $i <= (count($auths)-1);$i++){
                    if ($auths[$i] != 'jfusion'){
                        $authstr .= ','.$auths[$i];
                    }
                }
                $query = 'UPDATE #__config SET value = \''.$authstr.'\' WHERE name = \'auth\'';
                $db->setQuery($query);
                $db->query();
                if ($db->getErrorNum() != 0) {
                    $status['error'] = $db->stderr ();
                    return $status;
                }

            }
        }
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
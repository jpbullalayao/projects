<?php

/**
 * @package JFusion_universal
 * @author JFusion development team
 * @copyright Copyright (C) 2008 JFusion. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC' ) or die('Restricted access' );

/**
 * Load the JFusion framework
 */
require_once(JPATH_ADMINISTRATOR .DS.'components'.DS.'com_jfusion'.DS.'models'.DS.'model.jfusion.php');
require_once(JPATH_ADMINISTRATOR .DS.'components'.DS.'com_jfusion'.DS.'models'.DS.'model.abstractuser.php');
require_once(JPATH_ADMINISTRATOR .DS.'components'.DS.'com_jfusion'.DS.'models'.DS.'model.jplugin.php');

require_once(dirname(__FILE__).DS.'map.php');

/**
 * JFusion User Class for universal
 * For detailed descriptions on these functions please check the model.abstractuser.php
 * @package JFusion_universal
 */
class JFusionUser_universal extends JFusionUser {

	/**
	 * @param object $userinfo
	 *
	 * @return null|object
	 */
	function getUser($userinfo)
	{
		// initialise some objects
		/**
		 * @ignore
		 * @var $helper JFusionHelper_universal
		 */
		$helper = JFusionFactory::getHelper($this->getJname());

		$email = $helper->getFieldEmail();
		$username = $helper->getFieldUsername();

		//get the identifier
		list($identifier_type,$identifier) = $this->getUserIdentifier($userinfo,$username->field,$email->field);

		$db = JFusionFactory::getDatabase($this->getJname());

		$f = array('USERID','USERNAME', 'EMAIL', 'REALNAME', 'PASSWORD', 'SALT', 'GROUP', 'ACTIVE', 'INACTIVE','ACTIVECODE','FIRSTNAME','LASTNAME');
		$field = $helper->getQuery($f);
//        $query = 'SELECT '.$field.' NULL as reason, a.lastLogin as lastvisit'.
		$query = 'SELECT '.$field.' '.
			'FROM #__'.$helper->getTable().' '.
			'WHERE '.$identifier_type.'=' . $db->Quote($identifier);

		$db->setQuery($query );
		$result = $db->loadObject();
		if ( $result ) {
			$result->activation = '';
			if (isset($result->firstname)) {
				$result->name = $result->firstname;
				if (isset($result->lastname)) {
					$result->name .= ' '.$result->lastname;
				}
			}
			$result->block = 0;

			if ( isset($result->inactive) ) {
				$inactive = $helper->getFieldType('INACTIVE');
				if ($inactive->value['on'] == $result->inactive ) {
					$result->block = 1;
				}
			}
			if ( isset($result->active) ) {
				$active= $helper->getFieldType('ACTIVE');
				if ($active->value['on'] != $result->active ) {
					$result->block = 1;
				}
			}
			unset($result->inactive,$result->active);

			$group = $helper->getFieldType('GROUP','group');
			$userid = $helper->getFieldType('USERID','group');
			$groupt = $helper->getTable('group');
			if ( !isset($result->group_id) && $group && $userid && $groupt ) {
				$f = array('GROUP');
				$field = $helper->getQuery($f,'group');

				$query = 'SELECT '.$field.' '.
					'FROM #__'.$groupt.' '.
					'WHERE '.$userid->field.'=' . $db->Quote($result->userid);
				$db->setQuery($query );
				$result2 = $db->loadObject();

				if ($result2) {
					$result->group_id = base64_encode($result2->group_id);
				}
			}
		}
		return $result;
	}

	/**
	 * @return string
	 */
	function getJname()
	{
		return 'universal';
	}

	/**
	 * @param object $userinfo
	 *
	 * @return array
	 */
	function deleteUser($userinfo)
	{
		//setup status array to hold debug info and errors
		$status = array('error' => array(),'debug' => array());

		/**
		 * @ignore
		 * @var $helper JFusionHelper_universal
		 */
		$helper = JFusionFactory::getHelper($this->getJname());
		$userid = $helper->getFieldUserID();
		if (!$userid) {
			$status['error'][] = JText::_('USER_DELETION_ERROR') . ': '.JText::_('UNIVERSAL_NO_USERID_SET');
		} else {
			$db = JFusionFactory::getDatabase($this->getJname());
			$query = 'DELETE FROM #__'.$helper->getTable().' '.
				'WHERE '.$userid->field.'=' . $db->Quote($userinfo->userid);

			$db->setQuery($query);
			if (!$db->query()) {
				$status['error'][] = JText::_('USER_DELETION_ERROR') . ': ' .  $db->stderr();
			} else {
				$group = $helper->getFieldType('GROUP','group');
				if ( isset($group) ) {
					$userid = $helper->getFieldType('USERID','group');

					$maped = $helper->getMap('group');
					$andwhere = '';
					foreach ($maped as $value) {
						$field = $value->field;
						foreach ($value->type as $type) {
							switch ($type) {
								case 'DEFAULT':
									if ( $value->fieldtype == 'VALUE' ) {
										$andwhere .= ' AND '.$field.' = '.$db->Quote($value->value);
									}
									break;
							}
						}
					}

					$db = JFusionFactory::getDatabase($this->getJname());
					$query = 'DELETE FROM #__'.$helper->getTable('group').' '.
						'WHERE '.$userid->field.'=' . $db->Quote($userinfo->userid).$andwhere;
					$db->setQuery($query );
					if (!$db->query()) {
						$status['error'][] = JText::_('USER_DELETION_ERROR') . ': ' .  $db->stderr();
					} else {
						$status['debug'][] = JText::_('USER_DELETION'). ': ' . $userinfo->username;
					}
				}
			}
		}
		return $status;
	}

	/**
	 * @param object $userinfo
	 * @param array $options
	 *
	 * @return array
	 */
	function destroySession($userinfo, $options) {
		$cookie_backup = $_COOKIE;
		$_COOKIE = array();
		$_COOKIE['jfusionframeless'] = true;
		$status = JFusionJplugin::destroySession($userinfo, $options,$this->getJname(),'no_brute_force');
		$_COOKIE = $cookie_backup;
		$params = JFusionFactory::getParams($this->getJname());
		$status['debug'][] = JFusionFunction::addCookie($params->get('cookie_name'), '',0,$params->get('cookie_path'),$params->get('cookie_domain'),$params->get('secure'),$params->get('httponly'));
		return $status;
	}

	/**
	 * @param object $userinfo
	 * @param array $options
	 *
	 * @return array|string
	 */
	function createSession($userinfo, $options) {
		$status = array('error' => array(),'debug' => array());
		//do not create sessions for blocked users
		if (!empty($userinfo->block) || !empty($userinfo->activation)) {
			$status['error'][] = JText::_('FUSION_BLOCKED_USER');
		} else {
			$cookie_backup = $_COOKIE;
			$_COOKIE = array();
			$_COOKIE['jfusionframeless'] = true;
			$status = JFusionJplugin::createSession($userinfo, $options,$this->getJname(),'no_brute_force');
			$_COOKIE = $cookie_backup;
		}
		return $status;
	}

	/*
		function filterUsername($username)
		{
			//no username filtering implemented yet
			return $username;
		}
	*/
	/**
	 * @param object $userinfo
	 * @param object $existinguser
	 * @param array $status
	 *
	 * @return void
	 */
	function updatePassword($userinfo, &$existinguser, &$status)
	{
		/**
		 * @ignore
		 * @var $helper JFusionHelper_universal
		 */
		$helper = JFusionFactory::getHelper($this->getJname());
		$db = JFusionFactory::getDatabase($this->getJname());
		$maped = $helper->getMap();
		$params = JFusionFactory::getParams($this->getJname());

		$userid = $helper->getFieldUserID();
		if (!$userid) {
			$status['error'][] = JText::_('PASSWORD_UPDATE_ERROR') . ': '.JText::_('UNIVERSAL_NO_USERID_SET');
		} else {
			$qset = array();

			foreach ($maped as $value) {
				foreach ($value->type as $type) {
					switch ($type) {
						case 'PASSWORD':
							if ( isset($userinfo->password_clear) ) {
								$qset[] = $value->field.' = '.$db->quote($helper->getValue($value->fieldtype,$userinfo->password_clear,$userinfo));
							} else {
								$qset[] = $value->field.' = '.$db->quote($userinfo->password);
							}
							break;
						case 'SALT':
							if (!isset($userinfo->password_salt)) {
								$qset[] = $value->field.' = '.$db->quote($helper->getValue($value->fieldtype,$value->value,$userinfo));
							} else {
								$qset[] = $value->field.' = '.$db->quote($existinguser->password_salt);
							}
							break;
					}
				}
			}

			$query = 'UPDATE #__'.$helper->getTable().' '.
				'SET '.implode  ( ', '  , $qset  ).' '.
				'WHERE '.$userid->field.'=' . $db->Quote($existinguser->userid);

			$db->setQuery($query );
			if (!$db->query()) {
				$status['error'][] = JText::_('PASSWORD_UPDATE_ERROR')  . ': ' .$db->stderr();
			} else {
				$status['debug'][] = JText::_('PASSWORD_UPDATE') . ' ' . substr($existinguser->password,0,6) . '********';
			}
		}
	}

	/**
	 * @param object $userinfo
	 * @param object $existinguser
	 * @param array $status
	 *
	 * @return void
	 */
	function updateUsername($userinfo, &$existinguser, &$status)
	{

	}

	/**
	 * @param object $userinfo
	 * @param object $existinguser
	 * @param array $status
	 *
	 * @return void
	 */
	function updateEmail($userinfo, &$existinguser, &$status)
	{
		/**
		 * @ignore
		 * @var $helper JFusionHelper_universal
		 */
		$helper = JFusionFactory::getHelper($this->getJname());
		$params = JFusionFactory::getParams($this->getJname());

		$userid = $helper->getFieldUserID();
		$email = $helper->getFieldEmail();
		if (!$userid) {
			$status['error'][] = JText::_('EMAIL_UPDATE_ERROR') . ': '.JText::_('UNIVERSAL_NO_USERID_SET');
		} else if (!$email) {
			$status['error'][] = JText::_('EMAIL_UPDATE_ERROR') . ': '.JText::_('UNIVERSAL_NO_EMAIL_SET');
		} else {
			$db = JFusionFactory::getDatabase($this->getJname());
			$query = 'UPDATE #__'.$helper->getTable().' '.
				'SET '.$email->field.' = '.$db->quote($userinfo->email) .' '.
				'WHERE '.$userid->field.'=' . $db->Quote($existinguser->userid);
			$db->setQuery($query );
			if (!$db->query()) {
				$status['error'][] = JText::_('EMAIL_UPDATE_ERROR') . ': ' .$db->stderr();
			} else {
				$status['debug'][] = JText::_('EMAIL_UPDATE'). ': ' . $existinguser->email . ' -> ' . $userinfo->email;
			}
		}
	}

	/**
	 * @param object $userinfo
	 * @param object $existinguser
	 * @param array $status
	 *
	 * @return void
	 */
	function updateUsergroup($userinfo, &$existinguser, &$status)
	{
		//get the usergroup and determine if working in advanced or simple mode
		$usergroups = JFusionFunction::getCorrectUserGroups($this->getJname(),$userinfo);
		if (empty($usergroups)) {
			$status['error'][] = JText::_('GROUP_UPDATE_ERROR') . ' ' . JText::_('ADVANCED_GROUPMODE_MASTERGROUP_NOTEXIST');
		} else {
			$db = JFusionFactory::getDatabase($this->getJname());
			/**
			 * @ignore
			 * @var $helper JFusionHelper_universal
			 */
			$helper = JFusionFactory::getHelper($this->getJname());
			$params = JFusionFactory::getParams($this->getJname());

			$userid = $helper->getFieldUserID();
			$group = $helper->getFieldType('GROUP');

			if ( isset($group) && isset($userid) ) {
				$table = $helper->getTable();
				$type = 'user';
			} else {
				$table = $helper->getTable('group');
				$userid = $helper->getFieldType('USERID','group');
				$group = $helper->getFieldType('GROUP','group');
				$type = 'group';
			}
			if ( !isset($userid) ) {
				$status['debug'][] = JText::_('GROUP_UPDATE'). ': ' . JText::_('NO_USERID_MAPPED');
			} else if ( !isset($group) ) {
				$status['debug'][] = JText::_('GROUP_UPDATE'). ': ' . JText::_('NO_GROUP_MAPPED');
			} else if ( $type == 'user' ) {
				$usergroup = $usergroups[0];
				$query = 'UPDATE #__'.$table.' '.
					'SET '.$group->field.' = '.$db->quote(base64_decode($usergroup)) .' '.
					'WHERE '.$userid->field.'=' . $db->Quote($existinguser->userid);
				$db->setQuery($query );
				if (!$db->query()) {
					$status['error'][] = JText::_('GROUP_UPDATE_ERROR') . ': ' .$db->stderr();
				} else {
					$status['debug'][] = JText::_('GROUP_UPDATE'). ': ' . base64_decode($existinguser->group_id) . ' -> ' . base64_decode($usergroup);
				}
			} else {
				$maped = $helper->getMap('group');
				$andwhere = '';

				foreach ($maped as $key => $value) {
					$field = $value->field;
					foreach ($value->type as $type) {
						switch ($type) {
							case 'DEFAULT':
								if ( $value->fieldtype == 'VALUE' ) {
									$andwhere .= ' AND '.$field.' = '.$db->Quote($value->value);
								}
								break;
						}
					}
				}
				//remove the old usergroup for the user in the groups table
				$query = 'DELETE FROM #__user_group WHERE '.$userid->field.'=' . $db->Quote($existinguser->userid) . $andwhere;
				$db->setQuery($query);
				if (!$db->query()) {
					$status['error'][] = JText::_('GROUP_UPDATE_ERROR') . $db->stderr();
				} else {
					foreach ($usergroups as $usergroup) {
						$addgroup = new stdClass;
						foreach ($maped as $key => $value) {
							$field = $value->field;
							foreach ($value->type as $type) {
								switch ($type) {
									case 'USERID':
										$addgroup->$field = $existinguser->userid;
										break;
									case 'GROUP':
										$addgroup->$field = base64_decode($usergroup);
										break;
									case 'DEFAULT':
										$addgroup->$field = $helper->getValue($value->fieldtype,$value->value,$userinfo);
										break;
								}
							}
						}
						if (!$db->insertObject('#__'.$helper->getTable('group'), $addgroup )) {
							$status['error'][] = JText::_('GROUP_UPDATE_ERROR') . ': ' .$db->stderr();
						} else {
							$status['debug'][] = JText::_('GROUP_UPDATE'). ': ' . base64_decode($existinguser->group_id) . ' -> ' . base64_decode($usergroup);
						}
					}
				}
			}
		}
	}

	/**
	 * @param object $userinfo
	 * @param object $existinguser
	 * @param array $status
	 *
	 * @return void
	 */
	function blockUser($userinfo, &$existinguser, &$status)
	{
		/**
		 * @ignore
		 * @var $helper JFusionHelper_universal
		 */
		$helper = JFusionFactory::getHelper($this->getJname());
		$userid = $helper->getFieldUserID();
		$active = $helper->getFieldType('ACTIVE');
		$inactive = $helper->getFieldType('INACTIVE');

		if (!$userid) {
			$status['error'][] = JText::_('ACTIVATION_UPDATE_ERROR') . ': '.JText::_('UNIVERSAL_NO_USERID_SET');
		} else if (!$active && !$inactive) {
			$status['debug'][] = JText::_('ACTIVATION_UPDATE_ERROR') . ': '.JText::_('UNIVERSAL_NO_ACTIVE_OR_INACTIVE_SET');
		} else {
			$userStatus = null;
			if ($userinfo->block) {
				if ( isset($inactive) ) {
					$userStatus = $inactive->value['on'];
				}
				if ( isset($active) ) {
					$userStatus = $active->value['off'];
				}
			} else {
				if ( isset($inactive) ) {
					$userStatus = $inactive->value['off'];
				}
				if ( isset($active) ) {
					$userStatus = $active->value['on'];
				}
			}
			if ($userStatus != null) {
				$db = JFusionFactory::getDatabase($this->getJname());
				$query = 'UPDATE #__'.$helper->getTable().' '.
					'SET '.$active->field.' = '. $db->Quote($userStatus) .' '.
					'WHERE '.$userid->field.'=' . $db->Quote($existinguser->userid);
				$db->setQuery($query );
				if (!$db->query()) {
					$status['error'][] = JText::_('ACTIVATION_UPDATE_ERROR') . ': ' .$db->stderr();
				} else {
					$status['debug'][] = JText::_('ACTIVATION_UPDATE'). ': ' . $existinguser->activation . ' -> ' . $userinfo->activation;
				}
			}
		}
	}

	/**
	 * @param object $userinfo
	 * @param object $existinguser
	 * @param array $status
	 *
	 * @return void
	 */
	function unblockUser($userinfo, &$existinguser, &$status)
	{
		/**
		 * @ignore
		 * @var $helper JFusionHelper_universal
		 */
		$helper = JFusionFactory::getHelper($this->getJname());
		$userid = $helper->getFieldUserID();
		$active = $helper->getFieldType('ACTIVE');
		$inactive = $helper->getFieldType('INACTIVE');
		if (!$userid) {
			$status['error'][] = JText::_('BLOCK_UPDATE_ERROR') . ': '.JText::_('UNIVERSAL_NO_USERID_SET');
		} else if (!$active && !$inactive) {
			$status['debug'][] = JText::_('ACTIVATION_UPDATE_ERROR') . ': '.JText::_('UNIVERSAL_NO_ACTIVE_OR_INACTIVE_SET');
		} else {
			$userStatus = null;
			if ( isset($inactive) ) $userStatus = $inactive->value['off'];
			if ( isset($active) ) $userStatus = $active->value['on'];

			$db = JFusionFactory::getDatabase($this->getJname());
			$query = 'UPDATE #__'.$helper->getTable().' '.
				'SET '.$active->field.' = '. $db->Quote($userStatus) .' '.
				'WHERE '.$userid->field.'=' . $db->Quote($existinguser->userid);
			$db->setQuery($query );
			if (!$db->query()) {
				$status['error'][] = JText::_('BLOCK_UPDATE_ERROR') . ': ' .$db->stderr();
			} else {
				$status['debug'][] = JText::_('BLOCK_UPDATE'). ': ' . $existinguser->block . ' -> ' . $userinfo->block;
			}
		}
	}

	/**
	 * @param object $userinfo
	 * @param object $existinguser
	 * @param array $status
	 *
	 * @return void
	 */
	function activateUser($userinfo, &$existinguser, &$status)
	{
		/**
		 * @ignore
		 * @var $helper JFusionHelper_universal
		 */
		$helper = JFusionFactory::getHelper($this->getJname());
		$userid = $helper->getFieldUserID();
		$activecode = $helper->getFieldType('ACTIVECODE');
		if (!$userid) {
			$status['error'][] = JText::_('ACTIVATION_UPDATE_ERROR') . ': '.JText::_('UNIVERSAL_NO_USERID_SET');
		} else if (!$activecode) {
			$status['debug'][] = JText::_('ACTIVATION_UPDATE_ERROR') . ': '.JText::_('UNIVERSAL_NO_ACTIVECODE_SET');
		} else {
			$db = JFusionFactory::getDatabase($this->getJname());
			$query = 'UPDATE #__'.$helper->getTable().' '.
				'SET '.$activecode->field.' = '. $db->Quote($userinfo->activation) .' '.
				'WHERE '.$userid->field.'=' . $db->Quote($existinguser->userid);
			$db->setQuery($query );
			if (!$db->query()) {
				$status['error'][] = JText::_('ACTIVATION_UPDATE_ERROR') . ': ' .$db->stderr();
			} else {
				$status['debug'][] = JText::_('ACTIVATION_UPDATE'). ': ' . $existinguser->activation . ' -> ' . $userinfo->activation;
			}
		}
	}

	/**
	 * @param object $userinfo
	 * @param object $existinguser
	 * @param array $status
	 *
	 * @return void
	 */
	function inactivateUser($userinfo, &$existinguser, &$status)
	{
		/**
		 * @ignore
		 * @var $helper JFusionHelper_universal
		 */
		$helper = JFusionFactory::getHelper($this->getJname());
		$userid = $helper->getFieldUserID();
		$activecode = $helper->getFieldType('ACTIVECODE');
		if (!$userid) {
			$status['error'][] = JText::_('ACTIVATION_UPDATE_ERROR') . ': '.JText::_('UNIVERSAL_NO_USERID_SET');
		} else if (!$activecode) {
			$status['debug'][] = JText::_('ACTIVATION_UPDATE_ERROR') . ': '.JText::_('UNIVERSAL_NO_ACTIVECODE_SET');
		} else {
			$db = JFusionFactory::getDatabase($this->getJname());
			$query = 'UPDATE #__'.$helper->getTable().' '.
				'SET '.$activecode->field.' = '. $db->Quote($userinfo->activation) .' '.
				'WHERE '.$userid->field.'=' . $db->Quote($existinguser->userid);
			$db->setQuery($query );
			if (!$db->query()) {
				$status['error'][] = JText::_('ACTIVATION_UPDATE_ERROR') . ': ' .$db->stderr();
			} else {
				$status['debug'][] = JText::_('ACTIVATION_UPDATE'). ': ' . $existinguser->activation . ' -> ' . $userinfo->activation;
			}
		}
	}

	/**
	 * @param object $userinfo
	 * @param array $status
	 *
	 * @return void
	 */
	function createUser($userinfo, &$status)
	{
		$params = JFusionFactory::getParams($this->getJname());

		$usergroups = JFusionFunction::getCorrectUserGroups($this->getJname(),$userinfo);
		if(empty($usergroups)) {
			$status['error'][] = JText::_('ERROR_CREATE_USER'). ' ' . JText::_('USERGROUP_MISSING');
		} else {
			$usergroup = $usergroups[0];
			/**
			 * @ignore
			 * @var $helper JFusionHelper_universal
			 */
			$helper = JFusionFactory::getHelper($this->getJname());

			$userid = $helper->getFieldUserID();
			if(empty($userid)) {
				$status['error'][] = JText::_('USER_CREATION_ERROR'). ': ' . JText::_('UNIVERSAL_NO_USERID_SET'). ': ' . $this->getJname();
			} else {
				$password = $helper->getFieldType('PASSWORD');
				if(empty($password)) {
					$status['error'][] = JText::_('USER_CREATION_ERROR'). ': ' . JText::_('UNIVERSAL_NO_PASSWORD_SET'). ': ' . $this->getJname();
				} else {
					$email = $helper->getFieldEmail();
					if(empty($email)) {
						$status['error'][] = JText::_('USER_CREATION_ERROR'). ': ' . $this->getJname() . ': ' . JText::_('UNIVERSAL_NO_EMAIL_SET');
					} else {
						$user = new stdClass;
						$maped = $helper->getMap();
						$db = JFusionFactory::getDatabase($this->getJname());
						foreach ($maped as $key => $value) {
							$field = $value->field;
							foreach ($value->type as $type) {
								switch ($type) {
									case 'USERID':
										$query = 'SHOW COLUMNS FROM #__'.$helper->getTable().' where Field = '.$db->Quote($field).' AND Extra like \'%auto_increment%\'';
										$db->setQuery($query);
										$fieldslist = $db->loadObject();
										if ($fieldslist) {
											$user->$field = NULL;
										} else {
											$f = $helper->getQuery(array('USERID'));
											$query = 'SELECT '.$f.' FROM #__'.$helper->getTable().' ORDER BY userid DESC LIMIT 1';
											$db->setQuery($query);
											$value = $db->loadResult();
											if (!$value) {
												$value = 1;
											} else {
												$value++;
											}
											$user->$field = $value;
										}
										break;
									case 'REALNAME':
										$user->$field = $userinfo->name;
										break;
									case 'FIRSTNAME':
										list($firstname,$lastname) = explode(' ',$userinfo->name ,2);
										$user->$field = $firstname;
										break;
									case 'LASTNAME':
										list($firstname,$lastname) = explode(' ',$userinfo->name ,2);
										$user->$field = $lastname;
										break;
									case 'GROUP':
										$user->$field = base64_decode($usergroup);
										break;
									case 'USERNAME':
										$user->$field = $userinfo->username;
										break;
									case 'EMAIL':
										$user->$field = $userinfo->email;
										break;
									case 'ACTIVE':
										if ($userinfo->block){
											$user->$field = $value->value['off'];
										} else {
											$user->$field = $value->value['on'];
										}
										break;
									case 'INACTIVE':
										if ($userinfo->block){
											$user->$field = $value->value['on'];
										} else {
											$user->$field = $value->value['off'];
										}
										break;
									case 'PASSWORD':
										if ( isset($userinfo->password_clear) ) {
											$user->$field = $helper->getValue($value->fieldtype,$userinfo->password_clear,$userinfo);
										} else {
											$user->$field = $userinfo->password;
										}
										break;
									case 'SALT':
										if (!isset($userinfo->password_salt)) {
											$user->$field = $helper->getValue($value->fieldtype,$value->value,$userinfo);
										} else {
											$user->$field = $userinfo->password_salt;
										}
										break;
									case 'DEFAULT':
										$val = isset($value->value) ? $value->value : null;
										$user->$field = $helper->getValue($value->fieldtype,$val,$userinfo);
										break;
								}
							}
						}
						//now append the new user data
						if (!$db->insertObject('#__'.$helper->getTable(), $user, $userid->field )) {
							//return the error
							$status['error'] = JText::_('USER_CREATION_ERROR'). ': ' . $db->stderr();
						} else {
							$group = $helper->getFieldType('GROUP');

							if ( !isset($group) ) {
								$groupuserid = $helper->getFieldType('USERID','group');
								$group = $helper->getFieldType('GROUP','group');
								if ( !isset($groupuserid) ) {
									$status['debug'][] = JText::_('GROUP_UPDATE'). ': ' . JText::_('NO_USERID_MAPPED');
								} else if ( !isset($group) ) {
									$status['debug'][] = JText::_('GROUP_UPDATE'). ': ' . JText::_('NO_GROUP_MAPPED');
								} else {
									$addgroup = new stdClass;

									$maped = $helper->getMap('group');
									foreach ($maped as $key => $value) {
										$field = $value->field;
										foreach ($value->type as $type) {
											switch ($type) {
												case 'USERID':
													$field2 = $userid->field;
													$addgroup->$field = $user->$field2;
													break;
												case 'GROUP':
													$addgroup->$field = base64_decode($usergroup);
													break;
												case 'DEFAULT':
													$addgroup->$field = $helper->getValue($value->fieldtype,$value->value,$userinfo);
													break;
											}
										}
									}
									if (!$db->insertObject('#__'.$helper->getTable('group'), $addgroup, $groupuserid->field )) {
										//return the error
										$status['error'] = JText::_('USER_CREATION_ERROR'). ': ' . $db->stderr();
									} else {
										//return the good news
										$status['debug'][] = JText::_('USER_CREATION');
										$status['userinfo'] = $this->getUser($userinfo);
									}
								}
							} else {
								//return the good news
								$status['debug'][] = JText::_('USER_CREATION');
								$status['userinfo'] = $this->getUser($userinfo);
							}
						}
					}
				}
			}
		}
	}
}

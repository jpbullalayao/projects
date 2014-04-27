<?php
/**
 * This is the jfusion content plugin file
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    Plugins
 * @subpackage DiscussionBot
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
*/

// no direct access
defined('_JEXEC' ) or die('Restricted access' );

/**
* Load the JFusion framework
*/
jimport('joomla.plugin.plugin');
jimport('joomla.html.pagination');
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusion' . DS . 'models' . DS . 'model.jfusion.php';
require_once JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusion' . DS . 'models' . DS . 'model.factory.php';
/**
 * ContentPlugin Class for jfusion
 *
 * @category   JFusion
 * @package    Plugins
 * @subpackage DiscussionBot
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
*/
class plgContentJfusion extends JPlugin
{
    var $params = false;
    var $mode = '';
    var $valid = false;
    var $jname = '';
    var $creationMode = '';
    var $template = 'default';
    /**
     * @var $article object
     */
    var $article = null;
    var $output = array();
    var $dbtask = '';
    var $ajax_request = 0;
    var $validity_reason = '';
    var $manual_plug = false;
    var $manual_threadid = 0;
    var $debug_mode = 0;
    var $helper = '';

    /**
    * Constructor
    *
    * For php4 compatibility we must not use the __constructor as a constructor for
    * plugins because func_get_args ( void ) returns a copy of all passed arguments
    * NOT references. This causes problems with cross-referencing necessary for the
    * observer design pattern.
     *
     * @param object &$subject The object to observe
     * @param array|object  $params   An array or object that holds the plugin configuration
     *
     * @since 1.5
     * @return void
    */
    public function plgContentJfusion(&$subject, $params)
    {
        parent::__construct($subject, $params);

        $this->loadLanguage('plg_content_jfusion', JPATH_ADMINISTRATOR);

        //retrieve plugin software for discussion bot
        if ($this->params===false) {
            if (is_array($params)) {
                $this->params = new JParameter( $params[params]);
            } else {
                $this->params = new JParameter( $params->params);
            }

        }

        $this->jname =& $this->params->get('jname',false);

        if ($this->jname !== false) {
            //load the plugin language file
            $this->loadLanguage('com_jfusion.plg_' . $this->jname, JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_jfusion');
        }

        //determine what mode we are to operate in
        if ($this->params->get('auto_create',0)) {
            $this->mode = ($this->params->get('test_mode',1)) ? 'test' : 'auto';
        } else {
            $this->mode = 'manual';
        }

        $this->creationMode =& $this->params->get('create_thread','load');

        $this->debug_mode = $this->params->get('debug', JRequest::getInt('debug_discussionbot',0));

        //define some constants
        $isJ16 = JFusionFunction::isJoomlaVersion('1.6');
        if (!defined('DISCUSSION_TEMPLATE_PATH')) {
            $url_path = ($isJ16) ? 'jfusion/' : '';
            define('DISCUSSBOT_URL_PATH', 'plugins/content/' . $url_path . 'discussbot/');
            $path = ($isJ16) ? 'jfusion' . DS : '';
            define('DISCUSSBOT_PATH', JPATH_SITE . DS . 'plugins' . DS . 'content' . DS . $path . 'discussbot' . DS);

            //let's first check for customized files in Joomla template directory
            $app = JFactory::getApplication();
            $JoomlaTemplateOverride = JPATH_BASE.DS.'templates'. DS .$app->getTemplate() . DS. 'html' . DS . 'plg_content_jfusion' . DS;
            if (file_exists($JoomlaTemplateOverride)) {
                define('DISCUSSION_TEMPLATE_PATH', $JoomlaTemplateOverride);
                define('DISCUSSION_TEMPLATE_URL', JFusionFunction::getJoomlaURL() . 'templates/' . $app->getTemplate() . '/html/plg_content_jfusion/');
            } else {
                define('DISCUSSION_TEMPLATE_PATH',JPATH_BASE.DS.'plugins'.DS.'content'.DS.$path.'discussbot'.DS.'tmpl'.DS.$this->template.DS);
                define('DISCUSSION_TEMPLATE_URL',JFusionFunction::getJoomlaURL().'plugins/content/'.$url_path.'discussbot/tmpl/'.$this->template.'/');
            }
        }

        //load the helper file
        $helper_path = DISCUSSBOT_PATH . 'helper.php';
        include_once $helper_path;
        $this->helper = new JFusionDiscussBotHelper($this->params, $this->jname, $this->mode, $this->debug_mode);

        //set option
        $this->helper->option = JRequest::getCmd('option');
    }


    /**
     * @param $subject
     * @param $isNew
     * @return bool
     */
    public function onAfterContentSave(&$subject, $isNew) {
        //check to see if a valid $content object was passed on
        $result = true;
        if (!is_object($subject)){
            JFusionFunction::raiseWarning(JText::_('DISCUSSBOT_ERROR'), JText::_('NO_CONTENT_DATA_FOUND'), 1);
            $result = false;
        } else {
            $this->article =& $subject;
	        $this->helper->setArticle($this->article);

            //make sure there is a plugin
            if (empty($this->jname)) {
                $result = false;
            } else {
                $this->helper->debug('onAfterContentSave called');

                //validate the article
	            $this->helper->getThreadStatus();
                // changed _validate to pass the $isNew flag, so that it will only check will happen depending on this flag
                list($this->valid, $this->validity_reason) = $this->helper->validate($isNew);
                $this->helper->debug('Validity: ' . $this->valid . '; ' . $this->validity_reason);

                //ignore auto mode if the article has been manually plugged
                $manually_plugged = preg_match('/\{jfusion_discuss (.*)\}/U', $this->article->introtext . $this->article->fulltext);

                $this->helper->debug('Checking mode...');
                if ($this->mode=='auto' && empty($manually_plugged)) {
                    $this->helper->debug('In auto mode');

                    if ($this->valid) {
                        $threadinfo = $this->helper->getThreadInfo();
                        $JFusionForum = JFusionFactory::getForum($this->jname);
                        $forumid = $JFusionForum->getDefaultForum($this->params, $this->article);

                        if (($this->creationMode=='load') ||
                            ($this->creationMode=='new' && ($isNew || (!$isNew && $this->helper->thread_status))) ||
                            ($this->creationMode=='reply' && $this->helper->thread_status)) {

                            //update/create thread
                            $this->helper->checkThreadExists();

                        } else {
                            $this->helper->debug('Article did not meet requirements to update/create thread');
                        }
                    } elseif ($this->creationMode=='new' && $isNew) {
                        $this->helper->debug('Failed validity test but creationMode is set to new and this is a new article');

                        $mainframe = JFactory::getApplication();
                        $publish_up = JFactory::getDate($this->article->publish_up)->toUnix();
                        $now = JFactory::getDate('now', $mainframe->getCfg('offset'))->toUnix();
                        if ($now < $publish_up || !$this->article->state) {
                            $this->helper->debug('Article set to be published in the future or is unpublished thus creating an entry in the database so that the thread is created when appropriate.');

                            //the publish date is set for the future so create an entry in the
                            //database so that the thread is created when the publish date arrives
                            $placeholder = new stdClass();
                            $placeholder->threadid = 0;
                            $placeholder->forumid = 0;
                            $placeholder->postid = 0;
                            JFusionFunction::updateDiscussionBotLookup($this->article->id, $placeholder, $this->jname);
                        }
                    }
                } elseif ($this->mode=='test' && empty($manually_plugged)) {
                    //recheck validity without stipulation
                    $this->helper->debug('In test mode thus not creating the article');
                    $threadinfo = $this->helper->getThreadInfo();
                    $JFusionForum = JFusionFactory::getForum($this->jname);
                    $content = '<u>' . $this->article->title . '</u><br />';
                    if (!empty($threadinfo)) {
                        $content .= JText::_('DISCUSSBOT_TEST_MODE') . '<img src="'.JFusionFunction::getJoomlaURL().DISCUSSBOT_URL_PATH.'images/check.png" style="margin-left:5px;"><br/>';
                        if ($threadinfo->published) {
                            $content .= JText::_('STATUS') . ': ' . JText::_('INITIALIZED_AND_PUBLISHED') . '<br />';
                        } else {
                            $content .= JText::_('STATUS') . ': ' . JText::_('INITIALIZED_AND_UNPUBLISHED') . '<br />';
                        }
                        $content .= JText::_('THREADID') . ': ' . $threadinfo->threadid . '<br />';
                        $content .= JText::_('FORUMID') . ': ' . $threadinfo->forumid . '<br />';
                        $content .= JText::_('FIRST_POSTID') . ': ' . $threadinfo->postid. '<br />';

                        $forumlist = $this->helper->getForumList();
                        if (!in_array($threadinfo->forumid, $forumlist)) {
                            $content .= '<span style="color:red; font-weight:bold;">' . JText::_('WARNING') . '</span>: ' . JText::_('FORUM_NOT_EXIST') . '<br />';
                        }

                        $forumthread = $JFusionForum->getThread($threadinfo->threadid);
                        if (empty($forumthread)) {
                            $content .= '<span style="color:red; font-weight:bold;">' . JText::_('WARNING') . '</span>: ' . JText::_('THREAD_NOT_EXIST') . '<br />';
                        }
                    } else {
                        $valid = ($this->valid) ? JText::_('JYES') : JText::_('JNO');
                        if (!$this->valid) {
                            $content .= JText::_('DISCUSSBOT_TEST_MODE') . '<img src="'.JFusionFunction::getJoomlaURL().DISCUSSBOT_URL_PATH.'images/x.png" style="margin-left:5px;"><br/>';
                            $content .= JText::_('VALID') . ': ' . $valid . '<br />';
                            $content .= JText::_('INVALID_REASON') . ': ' . $this->validity_reason . '<br />';
                        } else {
                            $content .= '<b>' . JText::_('DISCUSSBOT_TEST_MODE') . '</b><img src="'.JFusionFunction::getJoomlaURL().DISCUSSBOT_URL_PATH.'images/check.png" style="margin-left:5px;2><br/>';
                            $content .= JText::_('VALID_REASON') . ': ' . $this->validity_reason . '<br />';
                            $content .= JText::_('STATUS') . ': ' . JText::_('UNINITIALIZED_THREAD_WILL_BE_CREATED') . '<br />';
                            $forumid = $JFusionForum->getDefaultForum($this->params, $this->article);
                            $content .= JText::_('FORUMID') . ': ' . $forumid . '<br />';
                            $author = $JFusionForum->getThreadAuthor($this->params, $this->article);
                            $content .= JText::_('AUTHORID') . ': ' . $author . '<br />';
                        }
                    }
                    JError::raiseNotice('500', $content);
                } else {
                    $this->helper->debug('In manual mode...checking to see if article has been initialized');
                    $threadinfo = $this->helper->getThreadInfo();
                    if (!empty($threadinfo) && $threadinfo->published == 1 && $threadinfo->manual == 1) {
                        $this->helper->debug('Article has been initialized...updating thread');
                        //update thread
                        $this->helper->checkThreadExists();
                    } else {
                        $this->helper->debug('Article has not been initialized');
                    }
                }
                $this->helper->debug('onAfterContentSave complete', true);
            }
        }
        return $result;
    }

    /**
     * @param $subject
     * @param $params
     * @return bool
     */
    public function onPrepareContent(&$subject, $params)
    {
        $result = true;
        $this->article =& $subject;
	    $this->helper->setArticle($this->article);

        //reset some vars
        $this->manual_plug = false;
        $this->manual_threadid = 0;

        $this->validity_reason = '';
	    $this->helper->getThreadStatus();
        $this->helper->debug('onPrepareContent called');

        //check to see if a valid $content object was passed on
        if (!is_object($subject)){
            JFusionFunction::raiseWarning(JText::_('DISCUSSBOT_ERROR'), JText::_('NO_CONTENT_DATA_FOUND'), 1);
            $result = false;
        } else {
            //make sure there is a plugin
            if (empty($this->jname)) {
                $result = false;
            } else {
                //do nothing if this is a K2 category object
                if ($this->helper->option == 'com_k2' && get_class($this->article) == 'TableK2Category') {
                    $result = false;
                } else {
                    //set some variables needed throughout
                    $this->template = $this->params->get('template','default');

                    //make sure we have an actual article
                    if (!empty($this->article->id)) {
                        $this->dbtask = JRequest::getVar('dbtask', null, 'post');
                        $skip_new_check = ($this->dbtask=='create_thread') ? true : false;
                        $skip_k2_check = ($this->helper->option == 'com_k2' && in_array($this->dbtask, array('unpublish_discussion', 'publish_discussion'))) ? true : false;

                        list($this->valid, $this->validity_reason) = $this->helper->validate($skip_new_check, $skip_k2_check);
                        $this->helper->debug('Validity: ' . $this->valid . "; " . $this->validity_reason);

                        $this->ajax_request = JRequest::getInt('ajax_request',0);
	                    $ajax = $this->prepareAjaxResponce();
	                    if ($this->ajax_request) {
		                    //get and set the threadinfo
		                    $threadid = JRequest::getInt('threadid', 0, 'post');
		                    $threadinfo = $this->helper->getThreadInfo();
		                    if (empty($threadinfo))  {
			                    //could be a manual plug so let's get the thread info directly
			                    $JFusionForum = JFusionFactory::getForum($this->jname);
			                    $threadinfo = $JFusionForum->getThread($threadid);
			                    if (!empty($threadinfo)) {
				                    //let's set threadinfo
				                    $threadinfo->published = 1;
				                    $this->helper->getThreadInfo(false, $threadinfo);
				                    //override thread status
				                    $this->helper->thread_status = true;
				                    //set manual plug
				                    $this->manual_plug = true;
			                    } elseif ($this->dbtask != 'create_thread' && $this->dbtask != 'create_threadpost') {
				                    $ajax->message = JText::_('THREAD_NOT_FOUND');
				                    $this->renderAjaxResponce($ajax);
			                    }
		                    }
	                    }

	                    if ($this->dbtask == 'create_thread') {
		                    //this article has been manually initiated for discussion
		                    $this->createThread();
	                    } elseif (($this->dbtask == 'create_post' || $this->dbtask == 'create_threadpost') && $this->params->get('enable_quickreply',false)) {
		                    //a quick reply has been submitted so let's create the post
		                    $this->createPost();
	                    } elseif ($this->dbtask == 'unpublish_discussion') {
		                    //an article has been "uninitiated"
		                    $this->unpublishDiscussion();
	                    } elseif ($this->dbtask == 'publish_discussion') {
		                    //an article has been "reinitiated"
		                    $this->publishDiscussion();
		                    $threadinfo = $this->helper->getThreadInfo();
		                    if (!empty($threadinfo->published)) {
			                    //content is now published so display it
			                    $ajax->posts = $this->renderDiscussionContent($threadinfo);
		                    } else {
			                    $ajax->posts = null;
		                    }
		                    $ajax->status = true;
	                    }

	                    //save the visibility of the posts if applicable
	                    $show_discussion = JRequest::getVar('show_discussion','');
	                    if ($show_discussion!=='') {
		                    $JSession = JFactory::getSession();
		                    $JSession->set('jfusion.discussion.visibility',(int) $show_discussion);
	                    }

	                    //check for some specific ajax requests
	                    if ($this->ajax_request) {
		                    //check to see if this is an ajax call to update the pagination
		                    if ($this->params->get('show_posts',1) && $this->dbtask == 'update_posts') {
			                    $this->updatePosts();
		                    }  else if ($this->dbtask == 'update_debug_info') {
			                    $ajax->status = true;
		                    } else if ($show_discussion!=='') {
			                    $ajax->status = true;
			                    $ajax->message = 'jfusion.discussion.visibility set to '.$show_discussion;
		                    } else {
			                    $ajax->message = 'Discussion bot ajax request made but it doesn\'t seem to have been picked up';
		                    }
		                    $this->renderAjaxResponce($ajax);
	                    }
	                    //add scripts to header
	                    $this->helper->loadScripts();

                        if (empty($this->article->params) && !empty($this->article->parameters)) {
                            $this->article->params =& $this->article->parameters;
                        }

                        if (!empty($this->article->params)) {
                            $this->prepareContent();
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * joomla 1.6 compatibility layer
     *
     * @param $context
     * @param $article
     * @param $isNew
     */
    public function onContentAfterSave($context, &$article, $isNew)
	{
 	    $this->onAfterContentSave($article, $isNew);
	}

    /**
     * @param $context
     * @param $article
     * @param $params
     * @param int $limitstart
     */
    public function onContentPrepare($context, &$article, &$params, $limitstart=0)
	{
		if ( $context != 'com_content.featured' && $context != 'com_content.category' ) {
			//seems syntax has completely changed :(
			$this->onPrepareContent($article, $params);
		}
	}

    /**
     * @param $context
     * @param $article
     * @param $params
     * @param int $limitstart
     */
    public function onContentAfterDisplay($context, &$article, &$params, $limitstart=0)
	{
	    $view = JRequest::getVar('view');
	    $layout = JRequest::getVar('layout');

        if ($this->helper->option == 'com_content') {
            if ($view == 'featured' || ($view == 'category' && $layout == 'blog')) {
                $article->text = $article->introtext;
                $this->onPrepareContent($article, $params);
                $article->introtext = $article->text;
            }
        }
	}

	/**
	 * @param mixed $error
	 * @return string
	 */
	public function ajaxError($error) {
		//output the error
		$result = null;
		if (is_array($error)) {
			foreach($error as $err) {
				if ($result) {
					$result .= '<br /> - ' . $err;
				} else {
					$result = ' - ' . $err;
				}
			}
		} else {
			$result = $error;
		}
		return $result;
	}

	/**
	 * @return  stdClass
	 */
	public function prepareAjaxResponce() {
		$output = new stdClass;
		$output->debug = null;

		$output->posts = null;
		$output->buttons = null;
		$output->pagination = null;
		$output->status = false;
		$output->message = null;
		return $output;
	}

	/**
	 * @param stdClass $ajax
	 */
	public function renderAjaxResponce($ajax) {
	    $ajax->debug = $this->renderDebugOutput();
		$ajax->buttons = $this->renderButtons(true);
		if ($this->params->get('enable_pagination',1)) {
			$ajax->pagination = $this->updatePagination();
		}
		die(json_encode($ajax));
	}

	/**
	 * Returns the view for compare
	 *
	 * @return string
	 */
	public function view() {
		return ($this->helper->option == 'com_k2') ? 'item' : 'article';
	}

    /*
     * prepareContent
     */
    public function prepareContent()
    {
        JHTML::_( 'behavior.mootools' );
        $this->helper->debug('Preparing content');

        $content = '';
        //get the jfusion forum object
        $JFusionForum = JFusionFactory::getForum($this->jname);

        //find any {jfusion_discuss...} to manually plug
        $this->helper->debug('Finding all manually added plugs');
        preg_match_all('/\{jfusion_discuss (.*)\}/U',$this->article->text,$matches);
        $this->helper->debug(count($matches[1]) . ' matches found');

        foreach($matches[1] as $id) {
            //only use the first and get rid of the others
            if (empty($this->manual_plug)) {
                $this->manual_plug = true;
                $this->helper->debug('Plugging for thread id ' . $id);
                //get the existing thread information
                $threadinfo = $JFusionForum->getThread($id);

                if (!empty($threadinfo)) {
                    //manually plugged so definitely published
                    $threadinfo->published = 1;
                    //$threadinfo->manual = 1;
                    //set threadinfo
                    $this->helper->getThreadInfo(false, $threadinfo);

                    $this->helper->debug('Thread info found.');

                    //override thread status
                    $this->helper->thread_status = true;
                    $content = $this->render($threadinfo);
                    $this->article->text = str_replace("{jfusion_discuss $id}",$content,$this->article->text);
                } else {
                    $this->helper->debug('Thread info not found!');
                    $this->article->text = str_replace("{jfusion_discuss $id}",JText::_("THREADID_NOT_FOUND"),$this->article->text);
                }

            } else {
                $this->helper->debug('Removing plug for thread ' . $id);
                $this->article->text = str_replace("{jfusion_discuss $id}",'',$this->article->text);
            }
        }

        //check to see if the fulltext has a manual plug if we are in a blog view
        if (isset($this->article->fulltext)) {
            if (!$this->manual_plug && JRequest::getVar('view') != $this->view()) {
                preg_match('/\{jfusion_discuss (.*)\}/U',$this->article->fulltext,$match);
                if (!empty($match)) {
                    $this->helper->debug('No plugs in text but found plugs in fulltext');
                    $this->manual_plug = true;
                    $this->manual_threadid = $match[1];

                    //get the existing thread information
                    $threadinfo = $JFusionForum->getThread($this->manual_threadid);

                    if (!empty($threadinfo)) {
                        //manually plugged so definitely published
                        $threadinfo->published = 1;
                        //$threadinfo->manual = 1;

                        //create buttons for the manually plugged article
                        //set threadinfo
                        $this->helper->getThreadInfo(false, $threadinfo);
                        $content = $this->renderButtons(false);

                        //append the content
                        $this->article->text .= $content;
                    } else {
                        $this->article->text .= JText::_('THREADID_NOT_FOUND');
                    }
                }
            }
        }

        //check for auto mode if not already manually plugged
        if (!$this->manual_plug) {
            $this->helper->debug('Article not manually plugged...checking for other mode');
            $threadinfo = $this->helper->getThreadInfo();

            //create the thread if this article has been validated
            if ($this->mode=='auto') {
                $this->helper->debug('In auto mode');
                if ($this->valid) {
	                if ($threadinfo || $this->creationMode=='load' || ($this->creationMode=='view' && JRequest::getVar('view') == $this->view()) ) {
		                $status = $this->helper->checkThreadExists();
		                if ($status['action'] == 'created') {
			                $threadinfo = $status['threadinfo'];
		                }
	                }
                }
                if ($this->validity_reason != JText::_('REASON_NOT_IN_K2_ARTICLE_TEXT')) {
                    //a catch in case a plugin does something wrong
                    if (!empty($threadinfo->threadid) || $this->creationMode == 'reply') {
                        $content = $this->render($threadinfo);
                    }
                }
            } elseif ($this->mode=='test') {
                $this->helper->debug('In test mode');
                //get the existing thread information
                $content  = '<div class="jfusionclearfix" style="border:1px solid #ECF8FD; background-color:#ECF8FD; margin-top:10px; margin-bottom:10px;">';

                if (!empty($threadinfo)) {
                    $content .= '<b>' . JText::_('DISCUSSBOT_TEST_MODE') . '</b><img src="'.JFusionFunction::getJoomlaURL().DISCUSSBOT_URL_PATH.'images/check.png" style="margin-left:5px;"><br/>';
                    if ($threadinfo->published) {
                        $content .= JText::_('STATUS') . ': ' . JText::_('INITIALIZED_AND_PUBLISHED') . '<br />';
                    } else {
                        $content .= JText::_('STATUS') . ': ' . JText::_('INITIALIZED_AND_UNPUBLISHED') . '<br />';
                    }
                    $content .= JText::_('THREADID') . ': ' . $threadinfo->threadid . '<br />';
                    $content .= JText::_('FORUMID') . ': ' . $threadinfo->forumid . '<br />';
                    $content .= JText::_('FIRST_POSTID') . ': ' . $threadinfo->postid. '<br />';

                    $forumlist = $this->helper->getForumList();
                    if (!in_array($threadinfo->forumid, $forumlist)) {
                        $content .= '<span style="color:red; font-weight:bold;">' . JText::_('WARNING') . '</span>: ' . JText::_('FORUM_NOT_EXIST') . '<br />';
                    }

                    $forumthread = $JFusionForum->getThread($threadinfo->threadid);
                    if (empty($forumthread)) {
                        $content .= '<span style="color:red; font-weight:bold;">' . JText::_('WARNING') . '</span>: ' . JText::_('THREAD_NOT_EXIST') . '<br />';
                    }
                } else {
                    $valid = ($this->valid) ? JText::_('JYES') : JText::_('JNO');
                    if (!$this->valid) {
                        $content .= '<b>' . JText::_('DISCUSSBOT_TEST_MODE') . '</b><img src="'.JFusionFunction::getJoomlaURL().DISCUSSBOT_URL_PATH.'images/x.png" style="margin-left:5px;"><br/>';
                        $content .= JText::_('VALID') . ': ' . $valid . '<br />';
                        $content .= JText::_('INVALID_REASON') . ': ' . $this->validity_reason . '<br />';
                    } else {
                        $content .= '<b>' . JText::_('DISCUSSBOT_TEST_MODE') . '</b><img src="'.JFusionFunction::getJoomlaURL().DISCUSSBOT_URL_PATH.'images/check.png" style="margin-left:5px;"><br/>';
                        $content .= JText::_('VALID_REASON') . ': ' . $this->validity_reason . '<br />';
                        $content .= JText::_('STATUS') . ': ' . JText::_('UNINITIALIZED_THREAD_WILL_BE_CREATED') . '<br />';
                        $forumid = $JFusionForum->getDefaultForum($this->params, $this->article);
                        $content .= JText::_('FORUMID') . ': ' . $forumid . '<br />';
                        $author = $JFusionForum->getThreadAuthor($this->params, $this->article);
                        $content .= JText::_('AUTHORID') . ': ' . $author . '<br />';
                    }
                }
                $content .= '</div>';
            } elseif (!empty($threadinfo->manual)) {
                if (!empty($threadinfo->published)) {
                    $this->helper->debug('In manual mode but article has been initialized');
                    //this article was generated by the initialize button
                    $content = $this->render($threadinfo);
                } else {
                    $this->helper->debug('In manual mode but article was initialized then uninitialized');
                    $content = $this->renderButtons();
                }
            } else {
                $this->helper->debug('In manual mode');
                //in manual mode so just create the buttons
                if ($this->validity_reason != JText::_('REASON_NOT_IN_K2_ARTICLE_TEXT')) {
                    $content = $this->renderButtons();
                }
            }

            //append the content
            $this->article->text .= $content;
        }

        static $taskFormLoaded;
        if (empty($taskFormLoaded)) {
            $this->helper->debug('Adding task form');
            //tak on the task form; it only needs to be added once which will be used for create_thread
            $uri = JFactory::getURI();
            $url = $uri->toString(array('path', 'query', 'fragment'));
            $url = str_replace('&', '&amp;', $url);

            $content = <<<HTML
                <form style="display:none;" id="JFusionTaskForm" name="JFusionTaskForm" method="post" action="{$url}">
                    <input type="hidden" name="articleId" value="" />
                    <input type="hidden" name="dbtask" value="" />
                </form>
HTML;
            $this->article->text .= $content;

            $taskFormLoaded = 1;
        }

        $this->renderDebugOutput();
    }

    /**
     * renderDebugOutput
     *
     * @return string
     */
    public function renderDebugOutput()
    {
	    $debug_contents = '';
        if ($this->debug_mode) {
            require_once(JPATH_ADMINISTRATOR .DS.'components'.DS.'com_jfusion'.DS.'models'.DS.'model.debug.php');

	        if ($this->ajax_request) {
		        debug::$toggleScriptInited = true;
		        debug::$colorSchemeInited[0] = true;
	        }
            ob_start();
            debug::show($this->helper->debug_output, 'Discussion bot debug info',1);
            $debug_contents = ob_get_contents();
            ob_end_clean();

            if (!$this->ajax_request) {
                $this->article->text .= <<<HTML
                    <div id="jfusionDebugContainer{$this->article->id}">
                        {$debug_contents}
                    </div>
HTML;
            }
        }
	    return $debug_contents;
    }

    /*
     * createThread
     */
    public function createThread()
    {
        $JoomlaUser = JFactory::getUser();
        $mainframe = JFactory::getApplication();
        $return = JRequest::getVar('return');
        if ($return) {
            $url = base64_decode($return);
        } else {
            $uri = JFactory::getURI();
            $url = $uri->toString(array('path', 'query', 'fragment'));
            $url = JRoute::_($url, false);
            if ($uri->getVar('view')=='article') {
                //tak on the discussion jump to
                $url .= '#discussion';

                $JSession = JFactory::getSession();
                $JSession->set('jfusion.discussion.visibility',1);
            }
        }

        //make sure the article submitted matches the one loaded
        $submittedArticleId = JRequest::getInt('articleId', 0, 'post');

	    if (JFusionFunction::isJoomlaVersion()) {
			$editAccess = $JoomlaUser->authorise('core.edit', 'com_content');
	    } else {
		    $editAccess = $JoomlaUser->authorize('com_content', 'edit', 'content', 'all');
	    }

	    $ajaxEnabled = ($this->params->get('enable_ajax',1) && $this->ajax_request);
	    $ajax = $this->prepareAjaxResponce();

        if ($editAccess && $this->valid && $submittedArticleId == $this->article->id) {
            $status = $this->helper->checkThreadExists(1);

            if (!empty($status['error'])) {
	            if ($ajaxEnabled) {
		            $ajax->message = JText::_('DISCUSSBOT_ERROR') . ': ' . $this->ajaxError($status['error']);
	            } else {
		            JFusionFunction::raiseWarning(JText::_('DISCUSSBOT_ERROR'), $status['error'], 1);
	            }
            } else {
	            $ajax->status = true;
	            $msg = JText::sprintf('THREAD_CREATED_SUCCESSFULLY',$this->article->title);
	            if ($ajaxEnabled) {
		            $ajax->message = $msg;
	            } else {
		            JFusionFunction::raiseWarning(JText::_('SUCCESS'), $msg, 1);
	            }
            }
        } else {
	        $msg = JText::_('ACCESS_DENIED');
	        if ($ajaxEnabled) {
		        $ajax->message = $msg;
	        } else {
		        JFusionFunction::raiseWarning(JText::_('DISCUSSBOT_ERROR'), $msg, 1);
	        }
        }
	    if ($ajaxEnabled) {
		    $this->renderAjaxResponce($ajax);
	    } else {
		    $mainframe->redirect($url);
	    }
    }

    /*
     * createPost
     * @return void
     */
    public function createPost()
    {
	    $ajax = $this->prepareAjaxResponce();
        $JoomlaUser = JFactory::getUser();
        $JFusionForum = JFusionFactory::getForum($this->jname);

        //define some variables
        $allowGuests =& $this->params->get('quickreply_allow_guests',0);
        $ajaxEnabled = ($this->params->get('enable_ajax',1) && $this->ajax_request);

	    $jumpto = '';
	    $url = $this->helper->getArticleUrl($jumpto,'',false);
	    $msg = '';
        //process quick replies
        if (($allowGuests || !$JoomlaUser->guest) && !$JoomlaUser->block) {
            //make sure something was submitted
            $quickReplyText = JRequest::getVar('quickReply', '', 'POST');

            if (!empty($quickReplyText)) {
                //retrieve the userid from forum software
                if ($allowGuests && $JoomlaUser->guest) {
                    $userinfo = new stdClass();
                    $userinfo->guest = 1;

                    $captcha_verification = $JFusionForum->verifyCaptcha($this->params);
                } else {
                    $JFusionUser = JFusionFactory::getUser($this->jname);
                    $userinfo = $JFusionUser->getUser($JoomlaUser);
                    $userinfo->guest = 0;
                    //we have a user logged in so ignore captcha
                    $captcha_verification = true;
                }

                if ($captcha_verification) {
                    $threadinfo = null;
                    if ($this->dbtask=='create_threadpost') {
                        $status = $this->helper->checkThreadExists();
                        $threadinfo = $status['threadinfo'];
                    } elseif ($this->dbtask=="create_post") {
                        $threadinfo = $this->helper->getThreadInfo();
                    }

                    //create the post
                    if (!empty($threadinfo) && !empty($threadinfo->threadid) && !empty($threadinfo->forumid)) {
                        $status = $JFusionForum->createPost($this->params, $threadinfo, $this->article, $userinfo);

                        if (!empty($status['error'])){
                            if ($ajaxEnabled) {
	                            $ajax->message = JText::_('DISCUSSBOT_ERROR') . ': ' . $this->ajaxError($status['error']);
                            } else {
                                JFusionFunction::raiseWarning(JText::_('DISCUSSBOT_ERROR'), $status['error'],1);
                            }
                        } else {
                            if ($ajaxEnabled) {
                                //if pagination is set, set $limitstart so that we go to the added post
                                if ($this->params->get('enable_pagination',true)) {
                                    $replyCount = $JFusionForum->getReplyCount($threadinfo);
                                    $application = JFactory::getApplication();
                                    $limit = $application->getUserStateFromRequest( 'global.list.limit', 'limit_discuss', 5, 'int' );

                                    if ($this->params->get('sort_posts','ASC')=='ASC') {
                                        $limitstart = floor(($replyCount-1)/$limit) * $limit;
                                    } else {
                                        $limitstart = 0;
                                    }
                                    JRequest::setVar('limitstart_discuss',$limitstart);
                                }

                                $posts = $JFusionForum->getPosts($this->params, $threadinfo);
                                $this->helper->output = array();
                                $this->helper->output['posts'] = $this->preparePosts($posts);

                                //take note of the created post
                                $this->helper->output['submitted_postid'] = $status['postid'];
                                if (isset($status['post_moderated'])) {
                                    $this->helper->output['post_moderated'] = $status['post_moderated'];
                                } else {
                                    $this->helper->output['post_moderated'] = 0;
                                }

                                //output only the new post div
                                $this->helper->threadinfo =& $threadinfo;
	                            $ajax->posts = $this->helper->renderFile('default_posts.php');
	                            $ajax->status = true;
                            } else {
                                if ($this->params->get('jumpto_new_post',0)) {
                                    $jumpto = (isset($status['postid'])) ? "post" . $status['postid'] : '';
                                }
                                $url = $this->helper->getArticleUrl($jumpto,'',false);
                            }
	                        if (isset($status['post_moderated'])) {
		                        $msg = ($status['post_moderated']) ? 'SUCCESSFUL_POST_MODERATED' : 'SUCCESSFUL_POST';
	                        } else {
		                        $msg = 'SUCCESSFUL_POST';
	                        }

	                        if ($ajaxEnabled) {
		                        $ajax->message = JText::_($msg);
	                        } else {
		                        JFusionFunction::raiseWarning(JText::_('SUCCESS'), JText::_($msg),1);
	                        }
                        }
                    } else {
                        if ($ajaxEnabled) {
	                        $ajax->message = JText::_('DISCUSSBOT_ERROR') . ': ' . JText::_('THREADID_NOT_FOUND');
                        } else {
                            JFusionFunction::raiseWarning(JText::_('DISCUSSBOT_ERROR'), JText::_('THREADID_NOT_FOUND'),1);
                        }
                    }
                } else {
                    if ($ajaxEnabled) {
	                    $ajax->message = JText::_('DISCUSSBOT_ERROR') . ': ' . JText::_('CAPTCHA_INCORRECT');
                    } else {
                        JFusionFunction::raiseWarning(JText::_('DISCUSSBOT_ERROR'), JText::_('CAPTCHA_INCORRECT'),1);
                    }
                }
            } else {
                if ($ajaxEnabled) {
	                $ajax->message = JText::_('DISCUSSBOT_ERROR') . ': ' . JText::_('QUICKEREPLY_EMPTY');
                } else {
                    JFusionFunction::raiseWarning(JText::_('DISCUSSBOT_ERROR'), JText::_('QUICKEREPLY_EMPTY'),1);
                }
            }
        } else {
	        $msg = JText::_('ACCESS_DENIED');
	        if ($ajaxEnabled) {
		        $ajax->message = $msg;
	        } else {
		        JFusionFunction::raiseWarning(JText::_('DISCUSSBOT_ERROR'), $msg, 1);
	        }
        }
	    if ($ajaxEnabled) {
		    $this->renderAjaxResponce($ajax);
	    } else {
		    $mainframe = JFactory::getApplication();
		    $mainframe->redirect($url);
	    }
    }

    /*
     * unpublishDiscussion
     */
    public function unpublishDiscussion()
    {
        $JoomlaUser = JFactory::getUser();

        //make sure the article submitted matches the one loaded
        $submittedArticleId = JRequest::getInt('articleId', 0, 'post');
	    if (JFusionFunction::isJoomlaVersion()) {
		    $editAccess = $JoomlaUser->authorise('core.edit', 'com_content');
	    } else {
		    $editAccess = $JoomlaUser->authorize('com_content', 'edit', 'content', 'all');
	    }

	    $ajax = $this->prepareAjaxResponce();
        if ($editAccess && $this->valid && $submittedArticleId == $this->article->id) {
            $threadinfo = $this->helper->getThreadInfo();

            if (!empty($threadinfo)) {
                //created by discussion bot thus update the look up table
                JFusionFunction::updateDiscussionBotLookup($this->article->id, $threadinfo, $this->jname, 0, $threadinfo->manual);
            } else {
                //manually plugged thus remove any db plugin tags
                $jdb = JFactory::getDBO();
                //retrieve the original text
                $query = 'SELECT `introtext`, `fulltext` FROM #__content WHERE id = ' . $this->article->id;
                $jdb->setQuery($query);
                $texts = $jdb->loadObject();

                //remove any {jfusion_discuss...}
                $fulltext = preg_replace('/\{jfusion_discuss (.*)\}/U','',$texts->fulltext, -1, $fullTextCount);
                $introtext = preg_replace('/\{jfusion_discuss (.*)\}/U','',$texts->introtext, -1, $introTextCount);

                if (!empty($fullTextCount) || !empty($introTextCount)) {
                    $query = 'UPDATE #__content SET `fulltext` = ' . $jdb->Quote($fulltext) . ', `introtext` = ' .$jdb->Quote($introtext) . ' WHERE id = ' . (int) $this->article->id;
                    $jdb->setQuery($query);
                    $jdb->query();
                }
            }

	        $ajax->status = true;
	        $this->helper->getThreadStatus();
        } else {
	        if ($this->ajax_request) {
		        $ajax->message = JText::_('ACCESS_DENIED');
	        } else {
		        JFusionFunction::raiseWarning(JText::_('DISCUSSBOT_ERROR'), JText::_('ACCESS_DENIED'), 1);
	        }
        }
	    if ($this->ajax_request) {
		    $this->renderAjaxResponce($ajax);
	    } else {
		    $mainframe = JFactory::getApplication();
		    $mainframe->redirect($this->helper->getArticleUrl('','',false));
	    }
    }

    /*
     * publishDiscussion
     */
    public function publishDiscussion()
    {
        $JoomlaUser = JFactory::getUser();

        //make sure the article submitted matches the one loaded
        $submittedArticleId = JRequest::getInt('articleId', 0, 'post');
	    if (JFusionFunction::isJoomlaVersion()) {
		    $editAccess = $JoomlaUser->authorise('core.edit', 'com_content');
	    } else {
		    $editAccess = $JoomlaUser->authorize('com_content', 'edit', 'content', 'all');
	    }

	    $ajax = $this->prepareAjaxResponce();
        if ($editAccess && $this->valid && $submittedArticleId == $this->article->id) {
            $threadinfo = $this->helper->getThreadInfo();
            JFusionFunction::updateDiscussionBotLookup($this->article->id, $threadinfo, $this->jname, 1, $threadinfo->manual);

	        $ajax->status = true;
	        $this->helper->getThreadStatus();
        } else {
	        if ($this->ajax_request) {
		        $ajax->message = JText::_('ACCESS_DENIED');
	        } else {
		        JFusionFunction::raiseWarning(JText::_('DISCUSSBOT_ERROR'), JText::_('ACCESS_DENIED'), 1);
	        }
        }
	    if ($this->ajax_request) {
		    $this->renderAjaxResponce($ajax);
	    } else {
		    $mainframe = JFactory::getApplication();
		    $mainframe->redirect($this->helper->getArticleUrl('','',false));
	    }
    }

    /**
     * @param $threadinfo
     *
     * @return bool|string
     */
    public function render(&$threadinfo)
    {
        $this->helper->debug('Beginning rendering content');
        if (!empty($threadinfo)) {
            $JFusionForum = JFusionFactory::getForum($this->jname);
            $this->helper->reply_count = $JFusionForum->getReplyCount($threadinfo);
        }
        $view = JRequest::getVar('view');
        //let's only show quick replies and posts on the article view
        if ($view == $this->view()) {
            $JSession = JFactory::getSession();

            if (empty($threadinfo->published) && $this->creationMode != 'reply') {
                $this->helper->debug('Discussion content not displayed as this discussion is unpublished');
                $display = 'none';
                $generate_guts = false;
            } else {
                if ($JSession->get('jfusion.discussion.visibility',0) || empty($threadinfo) && $this->creationMode == 'reply') {
                    //show the discussion area if no replies have been made and creationMode is set to on first reply OR if user has set it to show
                    $display = 'block';
                } else {
                    $display = ($this->params->get('show_toggle_posts_link',1) && $this->params->get('collapse_discussion',1)) ? 'none' : 'block';
                }
                $generate_guts = true;
            }

            $content = '<div style="float:none; display:'.$display.';" id="discussion">';

            if ($generate_guts) {
                $content .= $this->renderDiscussionContent($threadinfo);
            }

            $content .= '</div>';
            //now generate the buttons in case the thread was just created
            $button_content  = $this->renderButtons();
            $content = $button_content . $content;
        } else {
            $content = $this->renderButtons();
        }

        return $content;
    }


    /**
     * @param $threadinfo
     *
     * @return bool|string
     */
    public function renderDiscussionContent(&$threadinfo)
    {
        $this->helper->debug('Rendering discussion content');

        //setup parameters
        $JFusionForum = JFusionFactory::getForum($this->jname);
        $allowGuests =& $this->params->get('quickreply_allow_guests',0);
        $JoomlaUser = JFactory::getUser();
        //make sure the user exists in the software before displaying the quick reply
        $JFusionUser = JFusionFactory::getUser($this->jname);
        $JFusionUserinfo = $JFusionUser->getUser($JoomlaUser);
        $action_url = $this->helper->getArticleUrl();
        $this->helper->output = array();
        $this->helper->output['reply_count'] = '';

        $show_form = ($allowGuests || (!$JoomlaUser->guest && !empty($JFusionUserinfo)) && !$JoomlaUser->block) ? 1 : 0;

	    $this->helper->output['post_pagination'] = '';
	    $this->helper->output['posts'] = '';
	    $this->helper->output['reply_form'] = '';
	    $this->helper->output['reply_form_error'] = '';
        if (!empty($threadinfo)) {
            if ($this->helper->reply_count === false || $this->helper->reply_count === null) {
                $this->helper->reply_count = $JFusionForum->getReplyCount($threadinfo);
            }
            //prepare quick reply box if enabled
            if ($this->params->get('enable_quickreply')){
                $threadLocked = $JFusionForum->getThreadLockedStatus($threadinfo->threadid);
                if ($threadLocked) {
                    $this->helper->output['reply_form_error'] = $this->params->get('locked_msg');
                } elseif ($show_form) {
                    if (!$JoomlaUser->guest && empty($JFusionUserinfo)) {
                        $this->helper->output['reply_form_error'] =  $this->jname . ': ' . JText::_('USER_NOT_EXIST');
                    } else {
                        $showGuestInputs = ($allowGuests && $JoomlaUser->guest) ? true : false;
                        $this->helper->output['reply_form']  = '<form id="jfusionQuickReply'.$this->article->id.'" name="jfusionQuickReply'.$this->article->id.'" method="post" action="'.$action_url.'">';
                        $this->helper->output['reply_form'] .= '<input type="hidden" name="dbtask" value="create_post" />';
                        $this->helper->output['reply_form'] .= '<input type="hidden" name="threadid" id="threadid" value="'.$threadinfo->threadid.'"/>';
                        $page_limitstart = JRequest::getInt('limitstart', 0);
                        if ($page_limitstart) {
                            $this->helper->output['reply_form'] .= '<input type="hidden" name="limitstart" value="'.$page_limitstart.'" />';
                        }
                        $this->helper->output['reply_form'] .= $JFusionForum->createQuickReply($this->params,$showGuestInputs);
                        $this->helper->output['reply_form'] .= '</form>';
                    }
                } else {
                    $this->helper->output['reply_form_error'] = $this->params->get('must_login_msg');
                }
            }

            //add posts to content if enabled
            if ($this->params->get('show_posts')) {
                //get the posts
                $posts = $JFusionForum->getPosts($this->params, $threadinfo);

                if (!empty($posts)){
                    $this->helper->output['posts'] = $this->preparePosts($posts);
                }

                if ($this->params->get('enable_pagination',1)) {
                    $application = JFactory::getApplication() ;
                    $limitstart = JRequest::getInt( 'limitstart_discuss', 0 );
                    $limit = (int) $application->getUserStateFromRequest( 'global.list.limit', 'limit_discuss', 5, 'int' );
                    if (!empty($this->helper->reply_count) && $this->helper->reply_count > 5) {
                        $pageNav = new JFusionPagination($this->helper->reply_count, $limitstart, $limit, '_discuss' );
                        $this->helper->output['post_pagination'] = '<form method="post" id="jfusionPaginationForm" name="jfusionPaginationForm" action="'.$action_url.'">';
                        $this->helper->output['post_pagination'] .= '<input type="hidden" name="jumpto_discussion" value="1" />';
                        $this->helper->output['post_pagination'] .= $pageNav->getListFooter();
                        $this->helper->output['post_pagination'] .= '</form>';
                    }
                }
            }
        } elseif ($this->creationMode=='reply') {
            //prepare quick reply box if enabled
            if ($show_form) {
                if (!$JoomlaUser->guest && empty($JFusionUserinfo)) {
                    $this->helper->output['reply_form_error'] =  $this->jname . ': ' . JText::_('USER_NOT_EXIST');
                } else {
                    $showGuestInputs = ($allowGuests && $JoomlaUser->guest) ? true : false;
                    $this->helper->output['reply_form']  = '<form id="jfusionQuickReply'.$this->article->id.'" name="jfusionQuickReply'.$this->article->id.'" method="post" action="'.$action_url.'">';
                    $this->helper->output['reply_form'] .= '<input type="hidden" name="dbtask" value="create_threadpost"/>';
                    $page_limitstart = JRequest::getInt('limitstart', 0);
                    if ($page_limitstart) {
                        $this->helper->output['reply_form'] .= '<input type="hidden" name="limitstart" value="'.$page_limitstart.'" />';
                    }
                    $this->helper->output['reply_form'] .= $JFusionForum->createQuickReply($this->params,$showGuestInputs);
                    $this->helper->output['reply_form'] .= '</form>';
                }
            } else {
                $this->helper->output['reply_form_error'] = $this->params->get('must_login_msg');
            }
        }

        //populate the template
        $this->helper->threadinfo =& $threadinfo;
        $content = $this->helper->renderFile('default.php');
        return $content;
    }

    /**
     * @param bool $innerhtml
     *
     * @return bool|string
     */
    public function renderButtons($innerhtml = false)
    {
        $this->helper->debug('Rendering buttons');

        //setup some variables
        $threadinfo = $this->helper->getThreadInfo();

        $JUser = JFactory::getUser();
        $itemid =& $this->params->get('itemid');
        $link_text =& $this->params->get('link_text');
        $link_type=& $this->params->get('link_type','text');
        $link_mode=& $this->params->get('link_mode','always');
        $blog_link_mode=& $this->params->get('blog_link_mode','forum');
        $linkHTML = ($link_type=='image') ? '<img style="border:0;" src="'.$link_text.'">' : $link_text;
        $linkTarget =& $this->params->get('link_target','_parent');
        if ($this->helper->isJ16) {
            if ($this->helper->option == 'com_content') {
                $article_access = $this->article->params->get('access-view');
            } elseif ($this->helper->option == 'com_k2') {
                $article_access = (in_array($this->article->access, $JUser->authorisedLevels()) && in_array($this->article->category->access, $JUser->authorisedLevels()));
            } else {
                $article_access = 1;
            }
        } else {
            if ($this->helper->option == 'com_content') {
                $article_access = ($this->article->access <= $JUser->get('aid', 0));
            } elseif ($this->helper->option == 'com_k2') {
                $article_access = ($this->article->access <= $JUser->get('aid', 0) && $this->article->category->access <= $JUser->get('aid', 0));
            } else {
                $article_access = 1;
            }
        }
        //prevent notices and warnings in default_buttons.php if there are no buttons to display
        $this->helper->output = array();
        $this->helper->output['buttons'] = array();
        /**
         * @ignore
         * @var $article_params JParameter
         */
        $attribs = $readmore_param = $article_params = null;
        $show_readmore = $readmore_catch = 0;
        if ($this->helper->option == 'com_content') {
            $attribs = new JParameter($this->article->attribs);

            if (isset($this->article->params)) {
                //blog view
                $article_params =& $this->article->params;
                $show_readmore = $article_params->get('show_readmore');
                $readmore_catch = ($this->helper->isJ16) ? $show_readmore : ((isset($this->article->readmore)) ? $this->article->readmore : 0);
            } elseif (isset($this->article->parameters)) {
                //article view
                $article_params =& $this->article->parameters;
                $readmore_catch = JRequest::getInt('readmore');
                $override = JRequest::getInt('show_readmore',false);
                $show_readmore = ($override!==false) ? $override : $article_params->get('show_readmore');
            }
            $readmore_param = 'show_readmore';
        } elseif ($this->helper->option == 'com_k2' && JRequest::getVar('view') == 'itemlist') {
            $article_params =& $this->article->params;
            $layout = JRequest::getVar('layout');
            if ($layout == 'category') {
                $readmore_param = 'catItemReadMore';
            } elseif ($layout == 'user') {
                $readmore_param = 'userItemReadMore';
            } else {
                $readmore_param = 'genericItemReadMore';
            }
            $show_readmore = $readmore_catch = $article_params->get($readmore_param);
        }

        //let's overwrite the read more link with our own
        //needed as in the case of updating the buttons via ajax which calls the article view
        $view = ($override = JRequest::getVar('view_override')) ? $override : JRequest::getVar('view');
        if ($view != $this->view() && $this->params->get('overwrite_readmore',1)) {
            //make sure the read more link is enabled for this article

            if (!empty($show_readmore) && !empty($readmore_catch)) {
                if ($article_access) {
                    $readmore_link = $this->helper->getArticleUrl();
                    if ($this->helper->option == 'com_content') {
                        if ($this->helper->isJ16) {
                            if (!empty($this->article->alternative_readmore)) {
        						$readmore = $this->article->alternative_readmore;
        						if ($this->article->params->get('show_readmore_title', 0) != 0) {
						            $readmore.= JHtml::_('string.truncate', ($this->article->title), $this->article->params->get('readmore_limit'));
        						}
                            } elseif ($this->article->params->get('show_readmore_title', 0) == 0) {
        						$readmore = JText::_('READ_MORE');
                            } else {
        						$readmore = JText::_('READ_MORE') . ': ';
        						$readmore.= JHtml::_('string.truncate', ($this->article->title), $this->article->params->get('readmore_limit'));
                            }
                        } else {
                            if ($attribs) {
                                $readmore = $attribs->get('readmore');
                            }
                        }
                    }
                    if (!empty($readmore)) {
                        $readmore_text = $readmore;
                    } else {
                        $readmore_text = JText::_('READ_MORE');
                    }
                } else {
                    $return_url = base64_encode($this->helper->getArticleUrl());
                    $readmore_link = JRoute::_('index.php?option=com_users&view=login&return='.$return_url);
                    $readmore_text = JText::_('READ_MORE_REGISTER');
                }

                $this->helper->output['buttons']['readmore']['href'] = $readmore_link;
                $this->helper->output['buttons']['readmore']['text'] = $readmore_text;
                $this->helper->output['buttons']['readmore']['target'] = '_self';

                //set it so that Joomla does not show its read more link
                if (isset($this->article->readmore)) {
                    $this->article->readmore = 0;
                }

                //hide the articles standard read more
                if ($readmore_param && $article_params) {
                    $article_params->set($readmore_param, 0);
                }
            }
        }

        //create a link to manually create the thread if it is not already
        $show_button = $this->params->get('enable_initiate_buttons',false);

        if ($show_button && empty($this->manual_plug)) {
            $user   = JFactory::getUser();
	        if (JFusionFunction::isJoomlaVersion()) {
		        $editAccess = $user->authorise('core.edit', 'com_content');
	        } else {
		        $editAccess = $user->authorize('com_content', 'edit', 'content', 'all');
	        }
            if ($editAccess) {
                if ($this->helper->thread_status) {
                    //discussion is published
                    $dbtask = 'unpublish_discussion';
                    $text = 'UNINITIATE_DISCUSSION';
                } elseif (isset($threadinfo->published)) {
                    //discussion is unpublished
                    $dbtask = 'publish_discussion';
                    $text = 'INITIATE_DISCUSSION';
                } else {
                    //discussion is uninitiated
                    $dbtask = 'create_thread';
                    $text = 'INITIATE_DISCUSSION';
                }

                $this->helper->output['buttons']['initiate']['href'] = 'javascript: void(0);';

                $vars  = '&view_override='.$view;
                $vars .= ($this->params->get('overwrite_readmore',1)) ? '&readmore='.$readmore_catch.'&show_readmore='.$show_readmore : '';

                $this->helper->output['buttons']['initiate']['js']['onclick'] = 'confirmThreadAction('.$this->article->id.",'$dbtask', '$vars', '{$this->helper->getArticleUrl()}');";
                $this->helper->output['buttons']['initiate']['text'] = JText::_($text);
                $this->helper->output['buttons']['initiate']['target'] = '_self';
            }
        }

/*
    <a class="jfusionRefreshLink" href="javascript:void(0);" onclick=""><?php echo JText::_('REFRESH_POSTS');?></a> <br/>
*/
	    if($view==$this->view() && $this->params->get('show_refresh_link',1) && $threadinfo) {
		    //class="jfusionRefreshLink"
		    $this->helper->output['buttons']['refresh']['href'] = 'javascript:void(0);';
		    $this->helper->output['buttons']['refresh']['js']['onclick'] = 'refreshPosts('.$threadinfo->threadid.');';
		    $this->helper->output['buttons']['refresh']['text'] = JText::_('REFRESH_POSTS');
		    $this->helper->output['buttons']['refresh']['target'] = $linkTarget;
	    }

        //create the discuss this link
        if ($this->helper->thread_status || $this->manual_plug) {
            if ($link_mode!="never") {
                $JFusionForum = JFusionFactory::getForum($this->jname);
                if ($this->helper->reply_count === false || $this->helper->reply_count === null) {
                    $this->helper->reply_count = $JFusionForum->getReplyCount($threadinfo);
                }

                if ($view==$this->view()) {
                    if ($link_mode=="article" || $link_mode=="always") {
                        $this->helper->output['buttons']['discuss']['href'] = JFusionFunction::routeURL($JFusionForum->getThreadURL($threadinfo->threadid), $itemid, $this->jname);
                        $this->helper->output['buttons']['discuss']['text'] = $linkHTML;
                        $this->helper->output['buttons']['discuss']['target'] = $linkTarget;

                        if ($this->params->get('enable_comment_in_forum_button',0)) {
                            $commentLinkText = $this->params->get('comment_in_forum_link_text', JText::_('ADD_COMMENT'));
                            $commentLinkHTML = ($this->params->get('comment_in_forum_link_type')=='image') ? '<img style="border:0;" src="'.$commentLinkText.'">' : $commentLinkText;
                            $this->helper->output['buttons']['comment_in_forum']['href'] = JFusionFunction::routeURL($JFusionForum->getReplyURL($threadinfo->forumid, $threadinfo->threadid), $itemid, $this->jname);
                            $this->helper->output['buttons']['comment_in_forum']['text'] = $commentLinkHTML;
                            $this->helper->output['buttons']['comment_in_forum']['target'] = $linkTarget;
                        }

                    }
                } elseif ($link_mode=="blog" || $link_mode=="always") {
                    if ($blog_link_mode=="joomla") {
                        //see if there are any page breaks
                        $joomla_text = (isset($this->article->fulltext)) ? $this->article->fulltext : $this->article->text;
                        $pagebreaks = substr_count($joomla_text, 'system-pagebreak');
                        $query = ($pagebreaks) ? "&limitstart=$pagebreaks" : '';
                        if ($article_access) {
                            $discuss_link = $this->helper->getArticleUrl('discussion', $query);
                        } else {
                            $return_url = base64_encode($this->helper->getArticleUrl('discussion', $query));
                            $discuss_link = JRoute::_('index.php?option=com_user&view=login&return='.$return_url);
                        }
                        $this->helper->output['buttons']['discuss']['href'] = 'javascript: void(0);';
                        $this->helper->output['buttons']['discuss']['js']['onclick'] = 'toggleDiscussionVisibility(1,\''.$discuss_link.'\');';
                        $this->helper->output['buttons']['discuss']['target'] = '_self';
                    } else {
                        $this->helper->output['buttons']['discuss']['href'] = JFusionFunction::routeURL($JFusionForum->getThreadURL($threadinfo->threadid), $itemid, $this->jname);
                        $this->helper->output['buttons']['discuss']['target'] = $linkTarget;
                    }

                    $this->helper->output['buttons']['discuss']['text'] = $linkHTML;

                    if ($this->params->get('enable_comment_in_forum_button',0)) {
                        $commentLinkText = $this->params->get('comment_in_forum_link_text', JText::_('ADD_COMMENT'));
                        $commentLinkHTML = ($this->params->get('comment_in_forum_link_type')=='image') ? '<img style="border:0;" src="'.$commentLinkText.'">' : $commentLinkText;
                        $this->helper->output['buttons']['comment_in_forum']['href'] = JFusionFunction::routeURL($JFusionForum->getReplyURL($threadinfo->forumid, $threadinfo->threadid), $itemid, $this->jname);
                        $this->helper->output['buttons']['comment_in_forum']['text'] = $commentLinkHTML;
                        $this->helper->output['buttons']['comment_in_forum']['target'] = $linkTarget;
                    }
                }
            }

            //show comments link
            if ($view==$this->view() && $this->params->get('show_toggle_posts_link',1)) {
                $this->helper->output['buttons']['showreplies']['href'] = 'javascript: void(0);';
                $this->helper->output['buttons']['showreplies']['js']['onclick'] = 'toggleDiscussionVisibility();';

                $JSession = JFactory::getSession();
                $show_replies = $JSession->get('jfusion.discussion.visibility',0);
                $text = (empty($show_replies)) ? 'HIDE_REPLIES' : 'SHOW_REPLIES';

                $this->helper->output['buttons']['showreplies']['text'] = JText::_($text);
                $this->helper->output['buttons']['showreplies']['target'] = '_self';
            }
        }

        $this->helper->threadinfo =& $threadinfo;
        if ($innerhtml) {
            $button_output = $this->helper->renderFile('default_buttons.php');
        } else {
            $button_output = <<<HTML
                <div class="jfusionclearfix" id="jfusionButtonArea{$this->article->id}">
                    {$this->helper->renderFile('default_buttons.php')}
                </div>
                <div class="jfusionclearfix jfusionButtonConfirmationBox" id="jfusionButtonConfirmationBox{$this->article->id}">
                </div>
HTML;
        }
        return $button_output;
    }

    /**
     * @param $posts
     *
     * @return array|string
     */
    public function preparePosts(&$posts)
    {
        $this->helper->debug('Preparing posts output');

        //get required params
        defined('_DATE_FORMAT_LC2') or define('_DATE_FORMAT_LC2','%A, %d %B %Y %H:%M');
        $date_format = $this->params->get('custom_date', _DATE_FORMAT_LC2);
        $showdate = intval($this->params->get('show_date'));
        $showuser = intval($this->params->get('show_user'));
        $showavatar = $this->params->get('show_avatar');
        $avatar_software = $this->params->get('avatar_software',false);
        $resize_avatar = $this->params->get('avatar_keep_proportional', false);
        $userlink = intval($this->params->get('user_link'));
        $link_software = $this->params->get('userlink_software',false);
        $userlink_custom = $this->params->get('userlink_custom',false);
        $character_limit = (int) $this->params->get('character_limit');
        $itemid = $this->params->get('itemid');
        $JFusionPublic = JFusionFactory::getPublic($this->jname);

        $JFusionForum = JFusionFactory::getForum($this->jname);
        $columns = $JFusionForum->getDiscussionColumns();
        if (empty($columns)) return '';

        $post_output = array();
        for ($i=0; $i<count($posts); $i++)
        {
            $p =& $posts[$i];
            $userid =& $p->{$columns->userid};
            $username = ($this->params->get('display_name') && isset($p->{$columns->name})) ? $p->{$columns->name} : $p->{$columns->username};
            $dateline =& $p->{$columns->dateline};
            $posttext =& $p->{$columns->posttext};
            $posttitle =& $p->{$columns->posttitle};
            $postid =& $p->{$columns->postid};
            $threadid =& $p->{$columns->threadid};
            $guest =& $p->{$columns->guest};
            $threadtitle = (isset($columns->threadtitle)) ? $p->{$columns->threadtitle} : '';

            $post_output[$i] = new stdClass();
            $post_output[$i]->postid = $postid;
            $post_output[$i]->guest = $guest;

            //get Joomla id
            $userlookup = JFusionFunction::lookupUser($JFusionForum->getJname(),$userid,false,$p->{$columns->username});

            //avatar
            if ($showavatar){
                if (!empty($avatar_software) && $avatar_software!='jfusion' && !empty($userlookup)) {
                    $post_output[$i]->avatar_src = JFusionFunction::getAltAvatar($avatar_software, $userlookup->id);
                } else {
                    $post_output[$i]->avatar_src = $JFusionForum->getAvatar($userid);
                }

                if (empty($post_output[$i]->avatar_src)) {
                    $post_output[$i]->avatar_src = JFusionFunction::getJoomlaURL().'components/com_jfusion/images/noavatar.png';
                }

                $size = ($resize_avatar) ? @getimagesize($post_output[$i]->avatar_src) : false;
                $maxheight = $this->params->get('avatar_height',80);
                $maxwidth = $this->params->get('avatar_width',60);
                //size the avatar to fit inside the dimensions if larger
                if ($size!==false && ($size[0] > $maxwidth || $size[1] > $maxheight)) {
                    $wscale = $maxwidth/$size[0];
                    $hscale = $maxheight/$size[1];
                    $scale = min($hscale, $wscale);
                    $post_output[$i]->avatar_width = floor($scale*$size[0]);
                    $post_output[$i]->avatar_height = floor($scale*$size[1]);
                } elseif ($size!==false) {
                    //the avatar is within the limits
                    $post_output[$i]->avatar_width = $size[0];
                    $post_output[$i]->avatar_height = $size[1];
                } else {
                    //getimagesize failed
                    $post_output[$i]->avatar_width = $maxwidth;
                    $post_output[$i]->avatar_height = $maxheight;
                }
            } else {
                $post_output[$i]->avatar_src = '';
                $post_output[$i]->avatar_height = '';
                $post_output[$i]->avatar_width = '';
            }

            //post title
            $post_output[$i]->subject_url = JFusionFunction::routeURL($JFusionForum->getPostURL($threadid,$postid), $itemid);
            if (!empty($posttitle)) {
                $post_output[$i]->subject = $posttitle;
            } elseif (!empty($threadtitle)) {
                $post_output[$i]->subject = 'Re: '.$threadtitle;
            } else {
                $post_output[$i]->subject = JText::_('NO_SUBJECT');
            }

            //user info
            if ($showuser) {
                $post_output[$i]->username_url = '';
                if ($userlink && empty($guest) && !empty($userlookup)) {
                    if ($link_software=='custom' && !empty($userlink_custom)  && !empty($userlookup)) {
                        $post_output[$i]->username_url = $userlink_custom.$userlookup->id;
                    } else {
                        $post_output[$i]->username_url = JFusionFunction::routeURL($JFusionForum->getProfileURL($userid, $username), $itemid);
                    }
                }
                $post_output[$i]->username = $username;
            } else {
                $post_output[$i]->username = '';
                $post_output[$i]->username_url  = '';
            }

            //post date
            if ($showdate){
                jimport('joomla.utilities.date');
                $tz_offset =& JFusionFunction::getJoomlaTimezone();
                $dateline += ($tz_offset * 3600);
                $date = gmstrftime($date_format, (int) $dateline);
                $post_output[$i]->date = $date;
            } else {
                $post_output[$i]->date = '';
            }

            //post body
            $post_output[$i]->text = $posttext;
            $status = $JFusionPublic->prepareText($post_output[$i]->text,'joomla', $this->params, $p);
            $original_text = '[quote="'.$username.'"]'."\n".$posttext."\n".'[/quote]';
            $post_output[$i]->original_text = $original_text;
            $JFusionPublic->prepareText($post_output[$i]->original_text, 'discuss', $this->params, $p);

            //apply the post body limit if there is one
            if (!empty($character_limit) && empty($status['limit_applied']) && JString::strlen($post_output[$i]->text) > $character_limit) {
                $post_output[$i]->text = JString::substr($post_output[$i]->text,0,$character_limit) . '...';
            }

            $toolbar = array();
            if ($this->params->get('enable_quickreply')){
                $JoomlaUser = JFactory::getUser();
                if ($this->params->get('quickreply_allow_guests',0) || !$JoomlaUser->guest) {
                    $toolbar[] = '<a href="javascript:void(0);" onclick="jfusionQuote('.$postid.');">'.JText::_('QUOTE').'</a>';
                }
            }

            if (!empty($toolbar)) {
                $post_output[$i]->toolbar = '| ' . implode(' | ', $toolbar) . ' |';
            } else {
                $post_output[$i]->toolbar = '';
            }
        }

        return $post_output;
    }

    /**
     * updatePagination
     *
     * @return string
     */
    public function updatePagination()
    {
        $this->helper->reply_count = JRequest::getVar('reply_count','');
        if ($this->helper->reply_count == '') {
            $JFusionForum = JFusionFactory::getForum($this->jname);
            $threadinfo = $this->helper->getThreadInfo();
            if (!empty($threadinfo)) {
                $this->helper->reply_count = $JFusionForum->getReplyCount($threadinfo);
            } else {
                $this->helper->reply_count = 0;
            }
        }

        $action_url = $this->helper->getArticleUrl('','',false);
        $application = JFactory::getApplication() ;

        $limit = (int) $application->getUserStateFromRequest( 'global.list.limit', 'limit_discuss', 5, 'int' );

        //set $limitstart so that the created post is shown
        if ($this->params->get('sort_posts','ASC')=='ASC') {
            $limitstart = floor(($this->helper->reply_count - 1)/$limit) * $limit;
        } else {
            $limitstart = 0;
        }

        //keep pagination from changing limit to all
        if ($limit == $this->helper->reply_count) {
            $reply_count = $this->helper->reply_count - 1;
        } else {
            $reply_count =& $this->helper->reply_count;
        }

        if (!empty($reply_count) && $reply_count > 5) {
            $pageNav = new JFusionPagination($reply_count, $limitstart, $limit, '_discuss');

            $pagination = '<form method="post" id="jfusionPaginationForm" name="jfusionPaginationForm" action="'.$action_url.'">';
            $pagination .= '<input type="hidden" name="jumpto_discussion" value="1"/>';
            $pagination .= $pageNav->getListFooter();
            $pagination .= '</form>';
        } else {
            $pagination = '';
        }

	    return $pagination;
    }

    /*
     * updatePosts
     */
    public function updatePosts()
    {
	    $ajax = $this->prepareAjaxResponce();
        if ($this->helper->thread_status) {

            $JFusionForum = JFusionFactory::getForum($this->jname);
            $threadinfo = $this->helper->getThreadInfo();
            $posts = $JFusionForum->getPosts($this->params, $threadinfo);
            $this->helper->output = array();
            $this->helper->output['posts'] = $this->preparePosts($posts);
            $ajax->posts = $this->helper->renderFile('default_posts.php');
	        $ajax->status = true;
        } else {
			$ajax->message = JText::_('NOT_PUBLISHED');
        }
	    $this->renderAjaxResponce($ajax);
    }
}
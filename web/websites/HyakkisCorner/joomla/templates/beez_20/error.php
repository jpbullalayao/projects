<?php
/**
 * @package    Joomla.Site
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$params = JFactory::getApplication()->getTemplate(true)->params;
$logo =  $params->get('logo');
$showRightColumn = 0;
$showleft = 0;
$showbottom = 0;

// get params
$color      = $params->get('templatecolor');
$navposition  = $params->get('navposition');

//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

<meta name="language" content="<?php echo $this->language; ?>" />

  <title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
  <link rel="stylesheet" href="http://www.hyakkiscorner.com/templates/beez_20/css/stylesheet.css">
<?php if ($this->error->getCode()>=400 && $this->error->getCode() < 500) {   ?>


    <!--<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/position.css" type="text/css" media="screen,projection" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/layout.css" type="text/css" media="screen,projection" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/print.css" type="text/css" media="Print" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/<?php echo htmlspecialchars($color); ?>.css" type="text/css" />-->
  
<!--<?php
  $files = JHtml::_('stylesheet', 'templates/'.$this->template.'/css/general.css', null, false, true);
  if ($files):
    if (!is_array($files)):
      $files = array($files);
    endif;
    foreach($files as $file):
?>
    <link rel="stylesheet" href="<?php echo $file;?>" type="text/css" />
<?php
    endforeach;
  endif;
?>
     <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/<?php echo htmlspecialchars($color); ?>.css" type="text/css" />
    <?php if ($this->direction == 'rtl') : ?>
      <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template_rtl.css" type="text/css" />
      <?php if (file_exists(JPATH_SITE . '/templates/'.$this->template.'/css/' . $color . '_rtl.css')) :?>
        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/<?php echo $color ?>_rtl.css" type="text/css" />
      <?php endif; ?>
    <?php endif; ?>
    <!--[if lte IE 6]>
      <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/ieonly.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <!--[if IE 7]>
      <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/ie7only.css" rel="stylesheet" type="text/css" />
    <![endif]-->


<style type="text/css">
      <!--
      #errorboxbody
      {margin:30px}
      #errorboxbody h2
      {font-weight:normal;
      font-size:1.5em}
      #searchbox
      {background:#eee;
      padding:10px;
      margin-top:20px;
      border:solid 1px #ddd
      }
      -->
</style>

<!-- Google Search -->
<script>
  (function() {
    var cx = '015016671645445230172:ps7pmazfwy0';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>  
  
</head>

<body>


<div id="wrapper">  
    <div id="top_wrapper">
       <div id="top_links">
          <a class="top_link" href="/about">About</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="top_link" href="/contact-us">Contact Us</a>
       </div> <!-- Top Links -->
    <div class="content_clear_floats"></div> <!-- Clears floats so we can see top wrapper -->
    </div> <!-- Top Wrapper -->

    <div id="header">
          <!--<div class="logoheader">
            <!--<?php
                $params = JFactory::getApplication()->getTemplate(true)->params;
                $logo =  $params->get('logo');
              ?>

              <?php jimport( 'joomla.application.module.helper' ); ?>-->

             <!--<h1 id="logo">

                                        <?php if ($logo): ?>
                                        <img src="<?php echo $this->baseurl ?>/<?php echo htmlspecialchars($logo); ?>"  alt="<?php echo htmlspecialchars($params->get('sitetitle'));?>" />
                                        <?php endif;?>
                                        <?php if (!$logo ): ?>
                                        <?php echo htmlspecialchars($params->get('sitetitle'));?>
                                        <?php endif; ?>
                                        <span class="header1">
                                        <?php echo htmlspecialchars($params->get('sitedescription'));?>
                                        </span></h1>-->
          <!--</div><!-- end logoheader -->
        <!--</div><!-- end header -->

<?php 
                  define('IN_MYBB', NULL);
                  global $mybb, $lang, $query, $db, $cache, $plugins, $displaygroupfields;
                  require_once('forum/global.php');
                  require_once('MyBBIntegrator.php');
                  $MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);
?>

                  <div id="panel">

                  <?php
                      if ($MyBBI->isLoggedIn() === true) {
                        echo "<span style=\"float:right;\">{$lang->welcome_current_time}</span>";
                        echo "$lang->welcome_back (<a href=\"{$mybb->settings['bburl']}/usercp.php\">{$lang->welcome_usercp}</a> — ";
                        
                        if ($mybb->user['usergroup'] == 4) { // If user is an admin
                        //if ($MyBBI->isSuperAdmin()) {
                          echo "<a href=\"{$mybb->settings['bburl']}/modcp.php\">{$lang->welcome_modcp}</a> — <a href=\"{$mybb->settings['bburl']}/admin/index.php\">{$lang->welcome_admin}</a> — "; 
                        } else if ($MyBBI->isModerator() === true ) {
                          echo "<a href=\"{$mybb->settings['bburl']}/modcp.php\">{$lang->welcome_modcp}</a> — "; 
                        } else { // Just a user, do nothing
                        }
                        echo "<a href=\"{$mybb->settings['bburl']}/member.php?action=logout&amp;logoutkey={$mybb->user['logoutkey']}\">{$lang->welcome_logout}</a>)<br />";  
                        echo "<div style=\"padding-top:5px\">";
                        echo "<span class=\"links\">";
                        echo "<a href=\"#\" onclick=\"MyBB.popupWindow('http://www.hyakkiscorner.com/forum/misc.php?action=buddypopup', 'buddyList', 350, 350);\">{$lang->welcome_open_buddy_list}</a>";
                        echo "</span>";
                        echo "<a href=\"{$mybb->settings['bburl']}/search.php?action=getnew\">{$lang->welcome_newposts}</a> | <a href=\"{$mybb->settings['bburl']}/search.php?action=getdaily\">{$lang->welcome_todaysposts}</a> | <a href=\"{$mybb->settings['bburl']}/private.php\">{$lang->welcome_pms}</a> {$lang->welcome_pms_usage}</div>"; 
                      } else {
                        echo "<span style=\"float: right;\">{$lang->welcome_current_time}</span>
    <span id=\"quick_login\">{$lang->welcome_guest} (<a href=\"{$mybb->settings['bburl']}/member.php?action=login\" onclick=\"MyBB.quickLogin(); return false;\">{$lang->welcome_login}</a> &mdash; <a href=\"{$mybb->settings['bburl']}/member.php?action=register\">{$lang->welcome_register}</a>)</span>";                      
                      }                      
                  ?>
                    
                  </div>  
                  <div align="center">
                      <?php
                          $module = new stdClass();
                          $module->module = 'Top';
                          $module->params = "menutype=top";
                          echo JModuleHelper::renderModule($module);
                      ?>
                  </div>
     </div><!-- end header -->

     <div id="content_container">  
         <!--<jdoc:include type="component" />-->
         <p><?php echo JText::_('JERROR_AN_ERROR_HAS_OCCURRED'); ?><br />
             <?php echo JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'); ?></p>
       <p><a class="link" href="<?php echo $this->baseurl; ?>/index.php">Homepage</a></p>

       <p>Please contact admin <a class="link" href="http://www.hyakkiscorner.com/forum/member.php?action=profile&uid=4">Professor Ragna</a> if any more difficulties persist and to report this error.</p>

         <p>#<?php echo $this->error->getCode() ;?>&nbsp;<?php echo $this->error->getMessage();?></p> <br />
     </div><!-- end content_container -->  

            <!--<?php if ($this->debug) :
              echo $this->renderBacktrace();
            endif; ?>-->
      <footer>
                <div id="footnav"> <!-- Navigation -->
                    <b><li>Navigation</li></b>
                    <li><a href="./">Home</a></li>
                    <li><a href="./forum">Forum</a></li>
                    <li><a href="/guides">Guides</a></li>
                    <li><a href="/reviews">Reviews</a></li>
                    <li><a href="/stream">Stream</a></li>
                </div> <!-- Navigation -->
                
                <div id="footacc"> <!-- Accounts -->
                    <b><li>Accounts</li></b>
                    
                    <?php
  if ($MyBBI->isLoggedIn()) {
    echo "<li><a href=\"{$mybb->settings['bburl']}/usercp.php\">User CP</a></li>";
    
    if ($MyBBI->isModerator()) {
      echo "<li><a href=\"{$mybb->settings['bburl']}/modcp.php\">Moderator CP</a></li>"; 
    }
    
    if ($mybb->user['usergroup'] == 4) { // Admin
      echo "<li><a href=\"{$mybb->settings['bburl']}/admin/index.php\">Administrator CP</a></li>";
    }
    
    echo "<li><a href=\"{$mybb->settings['bburl']}/member.php?action=logout&amp;logoutkey={$mybb->user['logoutkey']}\">Log Out</a></li>";
  } else {
    echo "<li><a href=\"http://www.hyakkiscorner.com/forum/member.php?action=login\">Login</a></li>";
    echo "<li><a href=\"http://www.hyakkiscorner.com/forum/member.php?action=register\">Register</a></li>";
  } 
                    ?>
                         
                </div> <!-- Accounts -->
                
                <div id="footsup"> <!-- Support -->
                    <b><li>Support</li></b>
                    <li><a href="/contact-us">Contact Us</a></li>
                    <li><a href="/feedback">Feedback</a></li>
                  <li><a href="/account-support">Account Support</a></li>
                </div> <!-- Support -->

                <div id="footmed"> <!-- Media -->
                    <b><li>Media</li></b>
                    
                    <li><img src="/images/fb.gif" alt="Facebook icon" title="Facebook icon" height=15 width=15>&nbsp;<a href="#">Facebook</a></li>
                    <li><img src="/images/media_icon1.png" alt="Twitter icon" title="Twitter icon" height=15 width=15>&nbsp;<a href="http://www.twitter.com/HyakkisCorner">Twitter</a></li>
                    <li><img src="/images/instagram.png" alt="Instagram icon" title="Instagram icon" height=15 width=15>&nbsp;<a href="http://www.instagram.com/hyakkiscorner">Instagram</a></li>             
                </div> <!-- Media -->
                
                <div id="footabout"> <!-- About -->
                    <b><li>About</li></b>
                    <li><a href="/about">Hyakki's Corner</a></li>
                    <li><a href="/staff">Staff</a></li>
                </div> <!-- About -->

                <div id="footother"> <!-- Other --> <!-- BUG: MOVES UNDER ABOUT DIV WHEN PAGE RESIZES -->
                    <b><li>Other</li></b>
                    <li><a href="/sitemap">Sitemap</a></li>
                    <li><a href="/terms-of-use">Terms of Use</a></li>
                    <li><a href="/privacy-policy">Privacy Policy</a></li>                  
                </div> <!-- Other -->               
            
                <div id="copyright" class="text"> <!-- Copyright -->
                    &copy; 2013 Hyakki's Corner. All Rights Reserved.
                </div> <!-- Copyright -->
            </footer>
            <div id="search">
                <gcse:search></gcse:search>
            </div> <!-- Search -->  
      </div>  <!--end Wrapper -->
</body>
</html>
<?php } else { ?>
<?php
if (!isset($this->error)) {
  $this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
  $this->debug = false;
}
?>
  <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/error.css" type="text/css" />
</head>
<body>
  <div class="error">
    <div id="outline">
    <div id="errorboxoutline">
      <div id="errorboxheader"> <?php echo $this->title; ?></div>
      <div id="errorboxbody">
      <p><strong><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></strong></p>
        <ol>
          <li><?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
          <li><?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
          <li><?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
          <li><?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
          <li><?php echo JText::_('JERROR_LAYOUT_REQUESTED_RESOURCE_WAS_NOT_FOUND'); ?></li>
          <li><?php echo JText::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></li>
        </ol>
      <p><strong><?php echo JText::_('JERROR_LAYOUT_PLEASE_TRY_ONE_OF_THE_FOLLOWING_PAGES'); ?></strong></p>

        <ul>
          <li><a class="link" href="<?php echo $this->baseurl; ?>/index.php">Homepage</a></li>
          <li><a href="<?php echo $this->baseurl; ?>/index.php?option=com_search" title="<?php echo JText::_('JERROR_LAYOUT_SEARCH_PAGE'); ?>"><?php echo JText::_('JERROR_LAYOUT_SEARCH_PAGE'); ?></a></li>

        </ul>

        <p><!--<?php echo JText::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?>-->Please contact admin <a class="link" href="http://www.hyakkiscorner.com/forum/member.php?action=profile&uid=4">Professor Ragna</a> if any more difficulties persist and to report this error.</p>
      <div id="techinfo">
      <p><?php echo $this->error->getMessage(); ?></p>
      <p>
        <?php if ($this->debug) :
          echo $this->renderBacktrace();
        endif; ?>
      </p>
      </div>
      </div>
    </div>
    </div>
  </div>
</body>
</html>


<?php } ?>

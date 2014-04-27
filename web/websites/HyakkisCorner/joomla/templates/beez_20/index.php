<?php
/**
 * @package                Joomla.Site
 * @subpackage  Templates.beez_20
 * @copyright        Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license                GNU General Public License version 2 or later; see LICENSE.txt
 */ 
  
// No direct access.
defined('_JEXEC') or die;    
  
jimport('joomla.filesystem.file');

// check modules
$showRightColumn  = ($this->countModules('position-3') or $this->countModules('position-6') or $this->countModules('position-8'));
$showbottom      = ($this->countModules('position-9') or $this->countModules('position-10') or $this->countModules('position-11'));
$showleft      = ($this->countModules('position-4') or $this->countModules('position-7') or $this->countModules('position-5'));

if ($showRightColumn==0 and $showleft==0) {
  $showno = 0;
}

JHtml::_('behavior.framework', true);

// get params
$color        = $this->params->get('templatecolor');
$logo        = $this->params->get('logo');
$navposition    = $this->params->get('navposition');
$app        = JFactory::getApplication();
$doc        = JFactory::getDocument();
$templateparams    = $app->getTemplate(true)->params;

$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/css/stylesheet.css', $type = 'text/css', $media = 'screen,projection');
/*$doc->addStyleSheet($this->baseurl.'/templates/'.$this->template.'/css/content_styles.css', $type = 'text/css', $media = 'screen,projection');*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<!--[if lte IE 6]>
<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/ieonly.css" rel="stylesheet" type="text/css" />
<?php if ($color=="personal") : ?>
<style type="text/css">
#line {
  width:98% ;
}
.logoheader {
  height:200px;
}
#header ul.menu {
  display:block !important;
  width:98.2% ;
}
</style>
<?php endif; ?>
<![endif]-->

<!--[if IE 7]>
<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/ie7only.css" rel="stylesheet" type="text/css" />
<![endif]-->

<script type="text/javascript">
  var big ='<?php echo (int)$this->params->get('wrapperLarge');?>%';
  var small='<?php echo (int)$this->params->get('wrapperSmall'); ?>%';
  var altopen='<?php echo JText::_('TPL_BEEZ2_ALTOPEN', true); ?>';
  var altclose='<?php echo JText::_('TPL_BEEZ2_ALTCLOSE', true); ?>';
  var bildauf='<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/plus.png';
  var bildzu='<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/images/minus.png';
  var rightopen='<?php echo JText::_('TPL_BEEZ2_TEXTRIGHTOPEN', true); ?>';
  var rightclose='<?php echo JText::_('TPL_BEEZ2_TEXTRIGHTCLOSE', true); ?>';
  var fontSizeTitle='<?php echo JText::_('TPL_BEEZ2_FONTSIZE', true); ?>';
  var bigger='<?php echo JText::_('TPL_BEEZ2_BIGGER', true); ?>';
  var reset='<?php echo JText::_('TPL_BEEZ2_RESET', true); ?>';
  var smaller='<?php echo JText::_('TPL_BEEZ2_SMALLER', true); ?>';
  var biggerTitle='<?php echo JText::_('TPL_BEEZ2_INCREASE_SIZE', true); ?>';
  var resetTitle='<?php echo JText::_('TPL_BEEZ2_REVERT_STYLES_TO_DEFAULT', true); ?>';
  var smallerTitle='<?php echo JText::_('TPL_BEEZ2_DECREASE_SIZE', true); ?>';
</script>

<!-- Google Search Javascript -->
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
<!-- Google Analytics -->
<?php include_once("includes/analyticstracking.php") ?>  
  
<div id="wrapper">  
                <div id="top_wrapper">
                  <div id="top_links">
                    <!--<a href="#" 
                    onclick="
                    window.open(
                    'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href), 
                    'facebook-share-dialog', 
                    'width=626,height=436'); 
                    return false;">
                      <img src="/images/fb.gif" alt="Promote Hyakki's Corner on Facebook" title="Promote Hyakki's Corner on Facebook" height=15 width=15>
                    </a>-->
                    

                    
                    
                    
                    <a class="top_link" href="/about">About</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="top_link" href="/contact-us">Contact Us</a>
                  </div> <!-- Top Links -->
                  <div class="content_clear_floats"></div> <!-- Clears floats so we can see top wrapper -->
                </div> <!-- Top Wrapper -->
                <div id="header">
                
                                <div class="logoheader">
                                        <!--<h1 id="logo">

                                        <?php if ($logo): ?>
                                        <img src="<?php echo $this->baseurl ?>/<?php echo htmlspecialchars($logo); ?>"  alt="<?php echo htmlspecialchars($templateparams->get('sitetitle'));?>" />
                                        <?php endif;?>
                                        <?php if (!$logo ): ?>
                                        <?php echo htmlspecialchars($templateparams->get('sitetitle'));?>
                                        <?php endif; ?>
                                        <span class="header1">
                                        <?php echo htmlspecialchars($templateparams->get('sitedescription'));?>
                                        </span></h1>-->
                                </div><!-- end logoheader -->
                                
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
                      
  /*echo "<a href=\"#\" onclick=\"
    var settings = \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes\";
    settings = settings+\",350=\"+width;
    settings = settings+\",height=\"+height;
    window.open(http://www.hyakkiscorner.com/forum/misc.php?action=buddypopup, name, settings);
  \">{$lang->welcome_open_buddy_list}</a>";*/
                      
                  ?>
                    
                  </div>
                  
                  
                  
                  
                                        <div align="center">
                                            <jdoc:include type="modules" name="top" />
                                        </div>
                                        <div id="line">
                                        <div id="fontsize"></div>
                                        </div> <!-- end line -->

                        </div><!-- end header -->
                        <div id="content_container">  
                                <jdoc:include type="component" />
                        </div><!-- end content_container -->
  
                <footer>
                <div id="footnav"> <!-- Navigation -->
                    <b><li>Navigation</li></b>
                    <li><a href="./">Home</a></li>
                    <li><a href="./forum">Forum</a></li>
                    <li><a href="/guides">Guides</a></li>
                    <li><a href="/reviews">Reviews</a></li>
                    <li><a href="/stream">Stream</a></li>
                </div> <!-- Navigation -->
                
                <div id="footacc"> <!-- Navigation -->
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
                         
                </div> <!-- Navigation -->
                
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
<!--<div class="fb-like" data-href="http://facebook.com/cabanamana" data-width="450" data-colorscheme="dark" data-show-faces="false" data-send="false"></div>
  
       <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>-->
                </div><!-- all --> 
        </body>
        <script src="javascript/functions.js"></script>
</html>
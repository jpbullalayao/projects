<?php

/**
 * This is view file for logoutcheckerresult
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    ViewsAdmin
 * @subpackage Logoutcheckerresults
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
JFusionFunctionAdmin::displayDonate();
/**
 *     Load debug library
 */
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'models' . DS . 'model.debug.php';
/**
 * Output information about the server for future support queries
 */
?>

<form method="post" action="index.php" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="com_jfusion" />
	<input type="hidden" name="task" value="logoutcheckerresult" />
</form>
<div style="border:0 none ; margin:0; padding:0 5px; width: 800px; float: left;">
	<?php
	$title = JText::_('LOGOUT') . ' ' . JText::_('DEBUG');
    debug::show($this->debug, $title);
	?>
	<textarea rows="25" cols="110"><?php echo debug::getText($this->debug, $title) ?></textarea>
</div>
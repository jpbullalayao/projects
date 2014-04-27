<?php

/**
 * This is view file for synchistory
 *
 * PHP version 5
 *
 * @category   JFusion
 * @package    ViewsAdmin
 * @subpackage Synchistory
 * @author     JFusion Team <webmaster@jfusion.org>
 * @copyright  2008 JFusion. All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link       http://www.jfusion.org
 *
 * @var $this jfusionViewsynchistory
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
//display the paypal donation button
JFusionFunctionAdmin::displayDonate();
//allow for AJAX popups
JHTML::_('behavior.modal', 'a.modal');
?>
<script type="text/javascript">
<!--
var checked = false;
function applyAll() {
    var form = $('adminForm');
    checked = (checked === false);
    for(var i=0; i<form.elements.length; i++) {
        if (form.elements[i].type=="checkbox") {
            form.elements[i].checked = checked;
        }
    }
}
//-->
</script>

<form method="post" action="index.php" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="com_jfusion" />
	<input type="hidden" name="task" value="syncstatus" />

	<?php //why is this here?
	/*<input type="hidden" name="syncid" value="<?php echo $this->syncid;?>" />*/
	?>
	<table class="adminlist" style="border-spacing:1px;">
		<thead>
			<tr>
				<th class="title" width="20px">
					<input type='checkbox' onclick='applyAll();'/>
				</th>
				<th class="title" width="20px">
					<?php echo JText::_('ID'); ?>
				</th>
				<th class="title" >
					<?php echo JText::_('ACTION'); ?>
				</th>
				<th class="title" align="center">
					<?php echo JText::_('START_TIME'); ?>
				</th>
				<th class="title" align="center">
					<?php echo JText::_('END_TIME'); ?>
				</th>
				<th class="title" align="center">
					<?php echo JText::_('TOTAL_TIME'); ?>
				</th>
				<th class="title" align="center">
					<?php echo JText::_('ERRORS'); ?>
				</th>
				<th class="title" align="center">
					<?php echo JText::_('DETAILS'); ?>
				</th>
			</tr>
		</thead>
	<tbody>
		<?php
		$row_count = 0;
		if (empty($this->rows)) {
		    $this->rows = array();
		    JError::raiseWarning(500, JText::_('NO_USERSYNC_DATA'));
		}
		foreach ($this->rows as $record) {
		    ?><tr class="row<?php echo ($row_count % 2);?>"><?php
                $row_count++;
			    $syncdata = unserialize(base64_decode($record->syncdata));
			
			    ?>
			    <td>
			    	<input type="checkbox" name="syncid[<?php echo $record->syncid; ?>]" />
			    </td>
			    <td>
			    	<?php echo $record->syncid; ?>
			    </td>
			    <td>
			    	<?php echo $record->action; ?>
			    </td>
			    <td>
			    	<?php echo date('d/m/y : H:i:s', $record->time_start); ?>
			    </td>
			    <?php
			    if ($record->time_end) { ?>
			        <td>
			        	<?php echo date('d/m/y : H:i:s', $record->time_end); ?>
			        </td>
			        <td>
			        	<?php echo $this->getFormattedTimediff($record->time_start, $record->time_end); ?>
			        </td>
		        <?php
			    } else { ?>
			        <td>
			        </td>
			        <td>
			        	<?php echo JText::_('SYNC_NOT_FINISHED'); ?>
			        </td>
		        <?php
			    }
			
			    //get the total errors
			    $total_error = 0;
			    if (is_array($syncdata['slave_data'])) {
			        foreach ($syncdata['slave_data'] as $slave) {
			            $total_error = $total_error + $slave['error'];
			        }
			    }
			    echo '<td>' . $total_error . '</td>'; ?>
			    <td>
			    	<a class="modal" rel="{handler: 'iframe', size: {x: 650, y: 375}}" href="index.php?option=com_jfusion&amp;task=syncstatus&amp;tmpl=component&amp;syncid=<?php echo $record->syncid; ?>">
			    		<?php echo JText::_('CLICK_FOR_MORE_DETAILS'); ?>
			    	</a>
			    </td>
			</tr>
		    <?php
		}
		?>
		</tbody>
	</table>
</form>
<br/><br/><br/>
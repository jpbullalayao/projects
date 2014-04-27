<?php
/**
 * @package JFusion
 * @subpackage Views
 * @author JFusion development team
 * @copyright Copyright (C) 2008 JFusion. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *
 * @var jfusionViewPlugin $this
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div id="jfusionframeless" <?php echo $this->data->style; ?>>
    <div <?php echo $this->data->bodyAttributes; ?>>
		<?php echo $this->data->body; ?>
    </div>
</div>
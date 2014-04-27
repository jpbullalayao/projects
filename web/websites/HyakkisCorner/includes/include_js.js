/**
 * @author Professor Ragna
 */

//<script type="text/javascript">
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
//</script>

//<!-- Google Search Javascript -->
//<script>
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
//</script>
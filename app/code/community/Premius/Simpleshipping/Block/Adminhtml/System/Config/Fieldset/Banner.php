<?php
class Premius_Simpleshipping_Block_Adminhtml_System_Config_Fieldset_Banner extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $html = "<a target=\"_blank\" href=\"http://premius.net\"><img src=\"".$this->getSkinUrl('images/premius/logo.png')."\" /><br/>Need Magento installation, configuration or templating? Hire us!</a>";
        return $html;
    }
}
?>

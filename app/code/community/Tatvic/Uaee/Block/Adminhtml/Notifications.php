<?php
class Tatvic_Uaee_Block_Adminhtml_Notifications extends Mage_Adminhtml_Block_Template
{
    public function tvc_getMessage()
    {
        /*
          * Here you have check if there's a message to be displayed or not
          */
        $message = ' Google Tag Manager support for Google Analytics Enhanced Ecommerce module by Tatvic is going to get deprecated; the sunset date is 17th July, 2017!!';
        return $message;
    }
}
?>
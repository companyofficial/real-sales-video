<?php

class Sisow_Gateway_ideal extends Sisow_Gateway_Abstract
{
	public static function getCode()
    {
        return "ideal";
    }

    public static function getName()
    {
        return "iDEAL";
    }
		
	public function payment_fields()
    {
		$sisow = new Sisow_Helper_Sisow(get_option('sisow_merchantid'), get_option('sisow_merchantkey'), get_option('sisow_shopid'));
		$banks = array();
		
		$sisow->DirectoryRequest($banks, false, $this->get_option('testmode') == 'yes');
		
		$description = '';
		
		$description_text = $this->get_option('description');
		if(!empty($description_text))
			$description .= '<p>' . $description_text . '</p>';
		
		$description .= __('Choose your bank', 'woocommerce-sisow') . '<br/>';
		$description .= '<select id="ideal_issuer" name="ideal_issuer" class="required-entry">';
		$description .= '<option value="">' . __('Please choose...', 'woocommerce-sisow') . '</option>';
		foreach($banks as $k => $v)
			$description .= '<option value="'.$k.'">' . $v . '</option>';
		$description .= '</select>';
		$description .= '</p>';	
				
        echo $description;
    }
	
	public function validate_fields() 
	{ 
		if(empty($_POST['ideal_issuer']))
		{
			wc_add_notice( __('Please select your bank', 'woocommerce-sisow'), 'error' );
			return false; 
		}
		return true; 
	}
}
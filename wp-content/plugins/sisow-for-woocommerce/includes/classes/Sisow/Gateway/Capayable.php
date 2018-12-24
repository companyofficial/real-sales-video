<?php

class Sisow_Gateway_Capayable extends Sisow_Gateway_Abstract
{
	public static function NeedRedirect() { return true; }
	
	public static function getCode()
    {
        return "capayable";
    }

    public static function getName()
    {
        return "Gespreid betalen";
    }
	
	public static function canRefund()
	{
		return false;
	}
	
	public static function getWarning()
	{
		return array(
					'title'       => __( 'Warning', 'woocommerce-sisow' ),
					'type'        => 'title',
					'description' => __( 'An additional contract is required for this payment method, please contact <a href="mailto:sales@sisow.nl">sales@sisow.nl</a>.', 'woocommerce-sisow' ),
				);
	}
	
	public function payment_fields()
	{
		$description = '';
		$description .= '<div class="woocommerce-billing-fields">';
		$description .= '<p>U betaalt de bestelling in drie gelijke termijnen. De eerste termijn betaalt u nu direct via iDEAL. Van Capayable ontvangt u vervolgens een iDEAL link voor de tweede (dag 30) en derde (dag 60) termijn.</p>';		
		$description .= '<p>';
		$description .= __('Gender', 'woocommerce-sisow') . '<br/>';
		$description .= '<select name="capayable_gender">';
			$description .= '<option value="">-- ' . __('Please Choose', 'woocommerce-sisow') . ' --</option>';
			$description .= '<option value="m">'.__('Male', 'woocommerce-sisow').'</option>';
			$description .= '<option value="f">'.__('Female', 'woocommerce-sisow').'</option>';
		$description .= '</select>';
		$description .= '</p>';
		$description .= '<p>';
		$description .= __('Birthdate', 'woocommerce-sisow') . '<br/>';
		$description .= '<select name="capayable_birthday_day">';
			$description .= '<option value="">-- ' . __('Day', 'woocommerce-sisow') . ' --</option>';
			for($i = 1;$i < 32; $i++)
				$description .= '<option value="'.sprintf("%02d", $i).'">'.$i.'</option>';
		$description .= '</select>';
		$description .= '<select name="capayable_birthday_month">';
			$description .= '<option value="">-- ' . __('Month', 'woocommerce-sisow') . ' --</option>';
			$description .= '<option value="01">'.__('January', 'woocommerce-sisow').'</option>';
			$description .= '<option value="02">'.__('February', 'woocommerce-sisow').'</option>';
			$description .= '<option value="03">'.__('March', 'woocommerce-sisow').'</option>';
			$description .= '<option value="04">'.__('April', 'woocommerce-sisow').'</option>';
			$description .= '<option value="05">'.__('May', 'woocommerce-sisow').'</option>';
			$description .= '<option value="06">'.__('June', 'woocommerce-sisow').'</option>';
			$description .= '<option value="07">'.__('July', 'woocommerce-sisow').'</option>';
			$description .= '<option value="08">'.__('August', 'woocommerce-sisow').'</option>';
			$description .= '<option value="09">'.__('September', 'woocommerce-sisow').'</option>';
			$description .= '<option value="10">'.__('October', 'woocommerce-sisow').'</option>';
			$description .= '<option value="11">'.__('November', 'woocommerce-sisow').'</option>';
			$description .= '<option value="12">'.__('December', 'woocommerce-sisow').'</option>';
		$description .= '</select>';
		$description .= '<select name="capayable_birthday_year">';
			$description .= '<option value="">-- ' . __('Year', 'woocommerce-sisow') . ' --</option>';
			for($i = date("Y")-18;$i > date("Y") - 110; $i--)
					$description .= '<option value="'.$i.'">'.$i.'</option>';
		$description .= '</select>';
		$description .= '</p>';
		
		$description .= '<p>';
		$description .= __('CoC number', 'woocommerce-sisow') . '<small> (' . __('Only for B2B', 'woocommerce-sisow') . ')</small>' . '<br/>';
		$description .= '<input class="input-text" type="text" name="capayable_coc"/><br/>';
		$description .= '</p>';
		
		$description .= '<p>';
		$description .= '<a href="https://www.capayable.com/consument/gespreid-betalen" target="_blank"><img src="' . plugins_url( 'Images/'.$this->getCode().'.png', dirname(__FILE__) ) . '" style="float:right"/></a>';
		$description .= '</p>';
			
		$description .= '</div>';
		echo $description;
	}
	
	public function validate_fields() 
	{ 
		$validated = true;
		if(empty($_POST['capayable_gender']))
		{
			wc_add_notice( __('Please select your gender', 'woocommerce-sisow'), 'error' );
			$validated = false; 
		}
		
		if(empty($_POST['capayable_birthday_day']) || empty($_POST['capayable_birthday_month']) || empty($_POST['capayable_birthday_year']))
		{
			wc_add_notice( __('Please select your birthdate', 'woocommerce-sisow'), 'error' );
			$validated = false; 
		}
		
		return $validated; 
	}
}
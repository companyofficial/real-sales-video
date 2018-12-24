<?php

class Sisow_Gateway_Eps extends Sisow_Gateway_Abstract
{
	public static function getCode()
    {
        return "eps";
    }

    public static function getName()
    {
        return "EPS";
    }
	
	public static function canRefund()
	{
		return false;
	}
	
	public static function addScript()
	{
		wp_enqueue_script( "sisow-giropayeps-script", "https://bankauswahl.giropay.de/widget/v2/girocheckoutwidget.js", array('jquery'));
		wp_enqueue_style( "sisow-giropayeps-css", "https://bankauswahl.giropay.de/widget/v2/style.css");
	}
	
	public function payment_fields()
    {
		$description = '';
		
		$description_text = $this->get_option('description');
		if(!empty($description_text))
			$description .= '<p>' . $description_text . '</p>';
		
		$description .= '<p>';
		$description .= 'Mit eps Online-&Uuml;berweisung zahlen Sie einfach, schnell und sicher im Online-Banking Ihrer Bank. Im n&auml;chsten Schritt werden Sie direkt zum Online-Banking Ihrer Bank weitergeleitet, wo Sie die Zahlung durch Eingabe von PIN und TAN freigeben.';
		$description .= '</p>';	
		$description .= '<p>';
		$description .= __('Bankcode', 'woocommerce-sisow') . '<br/>';
		$description .= '<input type="text" id="eps_bic" name="eps_bic" value="" autocomplete="off" onkeyup="girocheckout_widget(this, event, \'bic\', \'3\')">';
		$description .= '</p>';	
				
        echo $description;
    }
	
	public function validate_fields() 
	{ 
		if(empty($_POST['eps_bic']))
		{
			wc_add_notice( __('Please insert a bank code', 'woocommerce-sisow'), 'error' );
			return false; 
		}
		return true; 
	}
}
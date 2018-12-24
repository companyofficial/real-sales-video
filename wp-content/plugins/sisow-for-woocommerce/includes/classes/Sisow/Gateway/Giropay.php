<?php

class Sisow_Gateway_Giropay extends Sisow_Gateway_Abstract
{
	public static function getCode()
    {
        return "giropay";
    }

    public static function getName()
    {
        return "Giropay";
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
		$description .= 'Mit giropay zahlen Sie einfach, schnell und sicher im Online-Banking Ihrer teilnehmenden Bank oder Sparkasse. Sie werden direkt zum Online-Banking Ihrer Bank weitergeleitet, wo Sie die &Uuml;berweisung durch Eingabe von PIN und TAN freigeben.';
		$description .= '</p>';	
		$description .= '<p>';
		$description .= __('Bankcode', 'woocommerce-sisow') . '<br/>';
		$description .= '<input type="text" id="giropay_bic" name="giropay_bic" value="" autocomplete="off" onkeyup="girocheckout_widget(this, event, \'bic\', \'0\')">';
		$description .= '</p>';	
		
        echo $description;
    }
	
	public function validate_fields() 
	{ 
		if(empty($_POST['giropay_bic']))
		{
			wc_add_notice( __('Please insert a bank code', 'woocommerce-sisow'), 'error' );
			return false; 
		}
		return true; 
	}
}
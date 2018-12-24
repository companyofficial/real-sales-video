<?php

class Sisow_Gateway_Visa extends Sisow_Gateway_Abstract
{
	public static function getCode()
    {
        return "visa";
    }

    public static function getName()
    {
        return "Visa";
    }
	
	public static function getWarning()
	{
		return array(
					'title'       => __( 'Warning', 'woocommerce-sisow' ),
					'type'        => 'title',
					'description' => __( 'An additional contract is required for this payment method, please contact <a href="mailto:sales@sisow.nl">sales@sisow.nl</a>.', 'woocommerce-sisow' ),
				);
	}
}
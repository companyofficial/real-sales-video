<?php

class Sisow_Gateway_Paypalec extends Sisow_Gateway_Abstract
{
	public static function getCode()
    {
        return "paypalec";
    }

    public static function getName()
    {
        return "PayPal";
    }
	
	public static function getWarning()
	{
		return array(
					'title'       => __( 'Warning', 'woocommerce-sisow' ),
					'type'        => 'title',
					'description' => __( 'Extra settings are required for this payment method, contact <a href="mailto:support@sisow.nl">support@sisow.nl</a> for more information.', 'woocommerce-sisow' ),
				);
	}
}
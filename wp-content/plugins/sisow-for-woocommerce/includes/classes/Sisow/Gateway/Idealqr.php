<?php

class Sisow_Gateway_Idealqr extends Sisow_Gateway_Abstract
{
	public static function getCode()
    {
        return "idealqr";
    }

    public static function getName()
    {
        return "iDEAL QR";
    }
	
	public static function canRefund()
	{
		return true;
	}
}
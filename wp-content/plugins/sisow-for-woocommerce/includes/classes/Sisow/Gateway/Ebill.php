<?php

class Sisow_Gateway_Ebill extends Sisow_Gateway_Abstract
{
	public static function NeedRedirect() { return false; }
	
	public static function getCode()
    {
        return "ebill";
    }

    public static function getName()
    {
        return "Ebill";
    }
	
	public function getIcon()
    {
		return "";
    }
	
	public static function canRefund()
	{
		return false;
	}
}
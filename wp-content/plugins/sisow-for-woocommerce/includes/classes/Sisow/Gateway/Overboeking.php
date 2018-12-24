<?php

class Sisow_Gateway_Overboeking extends Sisow_Gateway_Abstract
{
	public static function NeedRedirect() { return false; }
	
	public static function getCode()
    {
        return "overboeking";
    }

    public static function getName()
    {
        return "Overboeking";
    }
	
	public function getIcon()
    {
		return "";
    }
}
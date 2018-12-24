<?php

class Sisow_Gateway_Sofort extends Sisow_Gateway_Abstract
{
	public static function getCode()
    {
        return "sofort";
    }

    public static function getName()
    {
        return "Sofortbanking";
    }
}
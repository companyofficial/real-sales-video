<?php

class Sisow_Gateway_Mistercash extends Sisow_Gateway_Abstract
{
	public static function getCode()
    {
        return "mistercash";
    }

    public static function getName()
    {
        return "Bancontact/Mistercash";
    }
}
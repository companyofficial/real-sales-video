<?php

class Sisow_Gateway_Cbc extends Sisow_Gateway_Abstract
{
	public static function getCode()
    {
        return "cbc";
    }

    public static function getName()
    {
        return "CBC Betaalknop";
    }
}
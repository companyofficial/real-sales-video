<?php

class Sisow_Gateway_Belfius extends Sisow_Gateway_Abstract
{
	public static function getCode()
    {
        return "belfius";
    }

    public static function getName()
    {
        return "Belfius Pay Button";
    }
}
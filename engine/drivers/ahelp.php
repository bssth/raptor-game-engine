<?php

/*
	@last_edit 10.10.2015 by Mike
	@comment Deprecated
*/

class ahelpDriver 
{

    function __call($f, $a)
    {
        Raptor::Redirect('/');
    }

}

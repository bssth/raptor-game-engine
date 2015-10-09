<?php

/*
	@deprecated 10.10.2015 by Mike
*/

class ahelpDriver 
{

    function __call($f, $a)
    {
        Raptor::Redirect('/');
    }

}

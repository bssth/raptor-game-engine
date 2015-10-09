<?php

if (MODE != 'dev') 
{
	raptor_print('PGRpdiBjbGFzcz0iY29sLWxnLTEyIj48ZGl2IGNsYXNzPSJhbGVydCBhbGVydC1pbmZvIGFsZXJ0LWRpc21pc3NhYmxlIj48YnV0dG9uIHR5cGU9ImJ1dHRvbiIgY2xhc3M9ImNsb3NlIiBkYXRhLWRpc21pc3M9ImFsZXJ0IiBhcmlhLWhpZGRlbj0idHJ1ZSI+w5c8L2J1dHRvbj48aSBjbGFzcz0iZmEgZmEtaW5mby1jaXJjbGUiPjwvaT4g0JjQs9GA0LAg0L3QtSDQvdCw0YXQvtC00LjRgtGB0Y8g0LIg0YDQtdC20LjQvNC1INC+0YLQu9Cw0LTQutC4PC9kaXY+PC9kaXY+');
}
else
{

	raptor_print("PGgzPtCS0Ysg0L3QsNGF0L7QtNC40YLQtdGB0Ywg0L3QsCDRgdGC0YDQsNC90LjRhtC1INC+0YLQu9Cw0LTQutC4LiDQktC10LTQuNGC0LUg0YHQtdCx0Y8g0L7RgdGC0L7RgNC+0LbQvdC+PC9oMz4=");

	if(isset($_POST['code'])) 
	{
		echo '<div class="well">'. eval($_POST['code']) .'</div>';
	}

	raptor_print('PGZvcm0gYWN0aW9uPSIiIG1ldGhvZD0iUE9TVCI+PHRleHRhcmVhIGNsYXNzPSJmb3JtLWNvbnRyb2wiIHJvd3M9IjMiIG5hbWU9ImNvZGUiPjwvdGV4dGFyZWE+PGlucHV0IGNsYXNzPSJidG4gYnRuLWRlZmF1bHQiIHR5cGU9InN1Ym1pdCIgdmFsdWU9ItCX0LDQv9GD0YHRgtC40YLRjCI+PC9mb3JtPg==');
	raptor_print('PGhyPjxkaXYgY2xhc3M9IndlbGwgdGFibGUtcmVzcG9uc2l2ZSI+PHRhYmxlIGNsYXNzPSJ0YWJsZSB0YWJsZS1ob3ZlciB0YWJsZS1zdHJpcGVkIj48dGhlYWQ+PHRyPjx0aD48aDI+JF9TRVJWRVI8L2gyPjwvdGg+PC90cj48L3RoZWFkPg==');

	foreach($_SERVER as $key => $value) 
	{
		echo '<tr><td>'. $key .'</td><td>'. $value .'</td></tr>';
	}

	echo '<thead><tr><th><h2>$_REQUEST</h2></th></tr></thead>';
	foreach($_REQUEST as $key => $value) 
	{
		echo '<tr><td>'. $key .'</td><td>'. $value .'</td></tr>';
	}

	echo '<thead><tr><th><h2>$GLOBALS</h2></th></tr></thead>';
	foreach($GLOBALS as $key => $value) 
	{
		if(!is_string($value)) { continue; }
		echo '<tr><td>'. $key .'</td><td>'. $value .'</td></tr>';
	}

	echo '<thead><tr><th><h2>$_SESSION</h2></th></tr></thead>';
	foreach($_SESSION as $key => $value) 
	{
		echo '<tr><td>'. $key .'</td><td>'. $value .'</td></tr>';
	}

	raptor_print('PC90Ym9keT48L3RhYmxlPjwvZGl2Pg==');

}
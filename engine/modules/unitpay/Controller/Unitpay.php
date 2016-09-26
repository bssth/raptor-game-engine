<?php
	namespace Controller;
	
	class Unitpay
	{
		public function actionIndex() 
		{
			$payment = new \Unitpay\UnitPay(
				new \Unitpay\UnitPayEvent()
			);

			return $payment->getResult();
		}
	}
<?php

	namespace Unitpay;

	class UnitPayModel
	{
		private $mysqli;

		static function getInstance()
		{
			return new self();
		}

		function createPayment($unitpayId, $account, $sum, $itemsCount)
		{
			try {
				return \Database\Current::insert('unitpay_payments', [
					'unitpayId' => $unitpayId,
					'account' => $account,
					'sum' => $sum,
					'itemsCount' => $itemsCount,
					'dateCreate' => date("Y-m-d H:i:s"),
					'status' => 0
				]);
			}
			catch(\Raptor\Exception $e) {
				return null;
			}
		}

		function getPaymentByUnitpayId($unitpayId)
		{
			try {
				return (object)\Database\Current::getOne('unitpay_payments', ['unitpayId' => $unitpayId]);
			}
			catch(\Raptor\Exception $e) {
				return null;
			}
		}

		function confirmPaymentByUnitpayId($unitpayId)
		{
			try {
				return \Database\Current::update('unitpay_payments', ['unitpayId' => $unitpayId], ['status' => '1', 'dateComplete' => date("Y-m-d H:i:s")]);
			}
			catch(\Raptor\Exception $e) {
				return null;
			}
		}
		
		function getAccountByName($account)
		{
			try {
				return new \Auth\Char($account);
			}
			catch(\Raptor\Exception $e) {
				return null;
			}
		}
		
		function donateForAccount($account, $count)
		{
			try {
				$char = new \Auth\Char($account);
				return $char->money->giveMoney(\Raptor\Config::UP_CURRENCY, $count);
			}
			catch(\Raptor\Exception $e) {
				return null;
			}
		}
	}
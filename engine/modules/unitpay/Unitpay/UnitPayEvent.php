<?php
	/**
	 * Unitpay event class
	 */
	
	namespace Unitpay;
	
	class UnitPayEvent
	{
		public function check($params)
		{
			try {
				$unitPayModel = UnitPayModel::getInstance();

				if ($unitPayModel->getAccountByName($params['account'])) {
					return true;
				}
				return 'Character not found';
			} catch(Exception $e) {
				return $e->getMessage();
			}
		}

		public function pay($params)
		{
			$unitPayModel = UnitPayModel::getInstance();
			$countItems = floor($params['sum'] / (\Raptor\Config::UP_ITEM_PRICE));
			$unitPayModel->donateForAccount($params['account'], $countItems);
		}
	}
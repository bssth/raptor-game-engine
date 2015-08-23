<?php

/*
	@last_edit 22.08.2015
	@last_autor Mike
	@comment Инвентарь персонажа. Очень хрупкая система, поскольку требует тщательной настройки
	@todo Дополнительные проверки, дабы система не давала сбои
*/

class invDriver 
{

    function actionIndex() 
	{
		if(!isset($char)) 
		{
			$char = new Char();
		}
		$currency = Database::GetOne("config", array("mod" => "currency"));
		$inv_params = Database::GetOne("config", array("mod" => "inv_params"));
		$inv_actions = Database::GetOne("config", array("mod" => "inv_actions"));
        $main = new Templater;
        $main->import("boxes/inv_page.tpl");
        $main->setvar("%URL%", "http://" . $GLOBALS['url']);
        $main->setvar("%STORAGE_TPL_URL%", "/storage/tpl");
        $main->setvar("%YEAR%", date("Y"));
        $main->setvar("%CSS%", "<style>" . templater("css/game.css", array("%ROOT%" => "/storage/tpl")) . "</style>");
        $main->setvar("%GAME_TITLE%", $GLOBALS['name']);
        $main->setvar("%STORAGE_STATIC_URL%", "/storage/static");
		$result = '';
        foreach($char->inv->getItems() as $key => $value) 
		{
			if(!is_array($value)) 
			{ 
				continue; 
			}
			$addparams = array("%NAME%" => $value['name'], "%IMG%" => $value['image'], "%COST%" => $value['cost'], "%COUNT%" => $value['count'], "%C_NAME%" => $currency[$value['currency']]['name'], "%C_IMG%" => $currency[$value['currency']]['img'], "%CAT%" => $value['cat']);
			$id = $key;
			foreach ($inv_params as $skey => $svalue) 
			{
				if(!strstr($skey, "p_")) 
				{ 
					continue; 
				}
				$addparams["%" . $skey . "%"] = $char->inv->getParam($skey, $key);
				$addparams['%_PARAMS_%'] .= "<tr><td>". $inv_params[$skey]['name'] ."</td><td>". $char->inv->getParam($skey, $key) ."</td></tr>";
			}
			foreach ($inv_actions as $skey => $svalue) 
			{
				if(!strstr($skey, "act_")) 
				{ 
					continue; 
				}
				if(eval($svalue['eval'])) 
				{
					$addparams["%_SCR_ACTIONS_%"][] = "<a href='?act=". $skey ."&id=". $id ."'>". $svalue['name'] ."</a>";
					if($_GET['act'] == $skey and $_GET['id'] == $id) 
					{
						call_user_func("UseItem", $skey, $id);
					}
				}
			}
			$addparams["%_SCR_ACTIONS_%"] = (isset($addparams["%_SCR_ACTIONS_%"]) ? implode(" / ", $addparams["%_SCR_ACTIONS_%"]) : '');
			$result .= templater("boxes/inv_list.tpl", $addparams);
		}
		$main->setvar("%CONTENT%", $result);
        $main->renderEcho();
    }

}

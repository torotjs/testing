<?
#Первое задание

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "noUpdate");

function noUpdate(&$arFields) {
	$minusOneWeek = strtotime($arResult['DATE_CREATE']) + 604800;

	if($minusOneWeek > time()){
		global $APPLICATION;
		
	    $APPLICATION->throwException("Товар ".$arFields["NAME"]." был создан менее одной недели назад и не может быть изменен.");

	   	return false;
	}
}

#Второе задание

AddEventHandler("iblock", "OnBeforeIBlockElementDelete", "noDel");

function noDel($ID) {
	
	global $APPLICATION, $USER;

	$res = CIBlockElement::GetByID($ID);
	if($ar_res = $res->GetNext()){

		if($ar_res['SHOW_COUNTER'] >= 10000){

			\Bitrix\Main\Mail\Event::sendImmediate([
                'EVENT_NAME' => 'popular_deleted',
                'LID' => 's1',
                'C_FIELDS' => array("LOGIN" => $USER->GetLogin(), "USER_ID" => $USER->GetID(), "ITEM" => $ar_res['NAME'], "COUNT" => $ar_res['SHOW_COUNTER']),
                'N',
                1
            ]);
			
		
		    $APPLICATION->throwException("Нельзя удалить данный товар, так как он очень популярный на сайте");

		   	return false;

		}

	}

}

#Третье задание
# 1) Выводить лучше компонентом, например, news.list
# 2) В $arGroupBy нужно дописать группировку по свойству бренда
# 3) В $arSelect нужно указать свойство бренда
# 4) Можно отказаться от foreach и перенести код вывода в while
?>
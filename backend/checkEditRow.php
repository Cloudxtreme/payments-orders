<?php
 	$data = json_decode(file_get_contents("php://input"));
 	$errorList = array();
	array_push($errorList, "error");
	
 	/* Очистка данных от мусора */
	function clean($value = "") {
		$value = trim($value);				//Удаление пробелов
		$value = stripcslashes($value);		//Удаление экранирования символов	
		$value = strip_tags($value);		//Удаление HTML/PHP тегов
		$value = htmlspecialchars($value);	//Преобразуем специальные символы в HTML сущности

		return $value;
	}

	/* Получаем данные */
	$id	 			 = clean($data->id); 	
	$orderNumber	 = clean($data->orderNumber); 	
	$orderPrice		 = clean($data->orderPrice); 	
	$orderCurrency	 = clean($data->orderCurrency); 	
	$cardNumber		 = clean($data->cardNumber); 	
	$expirationMonth = clean($data->expirationMonth); 	
	$expirationYear  = clean($data->expirationYear);
	$firstName       = strtoupper(clean($data->firstName));
	$lastName        = strtoupper(clean($data->lastName));	
	$cvvCode         = clean($data->cvvCode);

	/* Проверка данных на пустоту */
	if(empty($id)) {
		array_push($errorList, "Нужно заполнить ID строки");	
	}

	if(empty($orderNumber)) {
		array_push($errorList, "Нужно заполнить номер заказа");
	}   
	if(empty($orderPrice)){
		array_push($errorList, "Нужно заполнить стоимость заказа");
	}		
	if(empty($orderCurrency)){
		array_push($errorList, "Нужно заполнить валюту заказа");	
	}					
	if(empty($cardNumber)){
		array_push($errorList, "Нужно заполнить номер карты");
	}		
	if(empty($expirationMonth)){
		array_push($errorList, "Нужно заполнить месяц срока дейсвтия карты");
	}
	if(empty($expirationYear)){
		array_push($errorList, "Нужно заполнить год срока действия карты");
	} 
	if(empty($firstName)){
		array_push($errorList, "Нужно заполнить имя владельца");
	}      
	if(empty($lastName)){
		array_push($errorList, "Нужно заполнить фамилию владельца");
	}       
	
	/* Проверка данных на соответсвие шаблонам*/
	if(!preg_match("/^[1-9][0-9]{0,10}$/", $id)){
		array_push($errorList, "ID строки не должен начинаться с 0 и должен содержать только цифры");	
	}

	if(!preg_match("/^[1-9][0-9]{0,9}$/", $orderNumber)) {
		array_push($errorList, "Номер заказа должен содержать от 1 до 10 цифр",
			"Номер заказа должен начинаться не с 0");
	}	
	if(!preg_match("/^[0-9]+(\.\d{2})?$/", $orderPrice)) {
		array_push($errorList, "Стоимость заказа должна быть в формате 0.00");
	}
	if(!preg_match("/[A-Z]{3}/", $orderCurrency)) {
		array_push($errorList, "Валюта заказа должна содержать 3 символа в верхнем регистре");
	}
	if(!preg_match("/[0-9]{16}/", $cardNumber)) {
		array_push($errorList, "Номер карты должен содержать 16 цифр");
	}
	if(!preg_match("/^[0-9]{1,2}$/", $expirationMonth)) {
		array_push($errorList, "Месяц срока действия должен содержать 1 или 2 цифры",
			"Месяц срока действия должен начинаться не с 0");
	}
	if(!preg_match("/[0-9]{4}/", $expirationYear)) {
		array_push($errorList, "Год срока действия должен содержать 4 цифры");		
	}
	if(!preg_match("/^[A-Z]+$/", $firstName)) {
		array_push($errorList, "Имя владельца должно содержать только латинские буквы верхнего регистра");
	}
	if(!preg_match("/^[A-Z]+$/", $lastName)) {
		array_push($errorList, "Фамилия владельца должна содержать только латинские буквы верхнего регистра");
	}

	$expirationMonth = (int) $expirationMonth;
	if(!($expirationMonth >= 1 && $expirationMonth <= 12)){
		array_push($errorList, "Месяц срока действия должен быть в диапазоне от 1 до 12");
	}

	$expirationYear = (int) $expirationYear;  
	$currentYear = (int) date("Y");

	if(!($expirationYear >= $currentYear)){		
		$nineYearsMore = $currentYear + 9;
		array_push($errorList, "Год срока действия должен быть в диапазоне от " . $currentYear . 
			" до " . $tenYearsMore );
	}	
	
	if(count($errorList) > 1) {
		header('Content-Type: application/json');
		echo json_encode($errorList);
		exit();
	} 
?>		

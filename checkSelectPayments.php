<?php	
	/* Очистка данных от мусора */
	function clean($value = "") {
		$value = trim($value);				//Удаление пробелов
		$value = stripcslashes($value);		//Удаление экранирования символов	
		$value = strip_tags($value);		//Удаление HTML/PHP тегов
		$value = htmlspecialchars($value);	//Преобразуем специальные символы в HTML сущности

		return $value;
	}

	//Получаем данные отправленные через Angular
	$data = json_decode(file_get_contents("php://input"));
	$errorList = array();
	array_push($errorList, "error");

	$orderNumber	 = clean($data->orderNumber); 	
	$orderCurrency	 = clean($data->orderCurrency); 	
	$cardNumber		 = clean($data->cardNumber); 	
	$firstName       = strtoupper(clean($data->firstName));
	$lastName        = strtoupper(clean($data->lastName));

	/* Проверка данных на пустоту */
	if(empty($cardNumber)){
		array_push($errorList, "Нужно заполнить номер карты");
	}	
	if(empty($firstName)){
		array_push($errorList, "Нужно заполнить имя владельца");
	}      
	if(empty($lastName)){
		array_push($errorList, "Нужно заполнить фамилию владельца");
	}
	
	/* Проверка данных на соответсвие шаблонам*/
	if(!empty($orderNumber) && !preg_match("/^[1-9][0-9]{0,9}$/", $orderNumber)) {
		array_push($errorList, "Номер заказа должен содержать от 1 до 10 цифр",
			"Номер заказа должен начинаться не с 0");
	}		
	if(!empty($orderCurrency) && !preg_match("/[A-Z]{3}/", $orderCurrency)) {
		array_push($errorList, "Валюта заказа должна содержать 3 символа в верхнем регистре");
	}
	if(!preg_match("/[0-9]{16}/", $cardNumber)) {
		array_push($errorList, "Номер карты должен содержать 16 цифр");
	}
	if(!preg_match("/^[A-Z]+$/", $firstName)) {
		array_push($errorList, "Имя владельца должно содержать только латинские буквы верхнего регистра");
	}
	if(!preg_match("/^[A-Z]+$/", $lastName)) {
		array_push($errorList, "Фамилия владельца должна содержать только латинские буквы верхнего регистра");
	}

	if(count($errorList) > 1) {
		header('Content-Type: application/json');
		echo json_encode($errorList);
		exit();
	} 
?>	
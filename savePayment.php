<?php 
	error_reporting( E_ERROR ); 
	//Проверка данных
	require("checkPayment.php"); 

	define("DB_NAME", "u815409800_payor");
	define("SERVER", "mysql.hostinger.ru");
	define("USER", "u815409800_admin");
	define("PASSWORD", "123456");

	//Записываем данные в БД
	//Открываем соединение с сервером
	$link = mysqli_connect(SERVER, USER, PASSWORD);
	if(!$link) {
		array_push($errorList, "Ошибка подключения (" . mysqli_connect_errno() . ") "
            . mysqli_connect_error());
		header('Content-Type: application/json');
		echo json_encode($errorList);
		exit();
	}

	//Определяем БД
	if(!mysqli_select_db($link, DB_NAME)){
		array_push($errorList, "Ошибка при определении базы данных: " . mysqli_error($link));
		//Закрываем соедение с сервером
		mysqli_close($link);
		header('Content-Type: application/json');
		echo json_encode($errorList);
		exit();
		// die("Ошибка при определении базы данных: " . mysqli_error($link));
	}

	//Вставляем данные в таблицу
	$query = "INSERT INTO payments" .  
		" ( number_order, price_order, currency, card_number, 
			expiration_month, expiration_year, first_name, 
			last_name, cvv_code	) VALUES ( " . $orderNumber .
			", " . $orderPrice . ", '" . $orderCurrency . "', " .
			$cardNumber . ", " . $expirationMonth . ", " .
			$expirationYear . ", '" . $firstName . "', '" .
			$lastName . "', " . $cvvCode . ");";    			
	
	if(mysqli_query($link, $query)){
		header('Content-Type: application/json');
		echo json_encode("Оплата успешно проведена!");
	} else {
		//Закрываем соедение с сервером					
		array_push($errorList, "Ошибка при сохранении в БД: " . mysqli_error($link));
		mysqli_close($link);	
		header('Content-Type: application/json');
		echo json_encode(json_decode($errorList));
		exit();											
	}

	//Закрываем соедение с сервером
	mysqli_close($link);				 
?>		

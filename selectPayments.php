<?php	
	error_reporting( E_ERROR ); 
	//Проверка данных
	require("checkSelectPayments.php"); 
	
	define("DB_NAME", "u815409800_payor");
	define("SERVER", "mysql.hostinger.ru");
	define("USER", "u815409800_admin");
	define("PASSWORD", "123456");

	//Выбираем данные из БД
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
	}

	// Выбираем данные из таблицы
	$query = "SELECT id, number_order, price_order, currency, card_number, ";
	$query = $query . "expiration_month, expiration_year, first_name, last_name FROM payments";
	$query = $query . " WHERE ";
	$query = $query . "card_number = " . $cardNumber;	
	$query = $query . " AND first_name = '" . $firstName . "'";	
	$query = $query . " AND last_name = '" . $lastName . "'";	


	 if(!empty($orderNumber)) {
	 	$query = $query . " AND number_order = " . $orderNumber ;
	 }
	 if(!empty($orderCurrency)) {
	 	$query = $query . " AND currency = '" . $orderCurrency . "'";	
	 }
	
	//Используем классы для возврата данных
	class SelectedData {

		public $htmlTable; 
		public $dataTable;

		function __construct() {
			$this->htmlTable = array();
			$this->dataTable = array();
		}			
	} 

	$objSelectedData = new SelectedData();

    $result = mysqli_query($link, $query);
	
	$table = array();
	$htmlTable = "";
	array_push($table, "table");
	if($result = mysqli_query($link, $query)) {				
		if(($countRows = mysqli_num_rows($result)) !== 0 ) {
			//Формируем таблицу для вывода   		
			while ($row = mysqli_fetch_array($result)) {	
				$objRowData = (object) $row;
				
				array_push($objSelectedData->dataTable, $objRowData);				

			}

			array_push($table, $htmlTable);
		}		
		$objSelectedData->htmlTable = $table;

		header('Content-Type: application/json');		
		// echo json_encode($table);
		echo json_encode($objSelectedData);
		mysqli_free_result($result);
	} else {
		//Ошибка при выборке				
		array_push($errorList, "Ошибка при выборе данных: " . mysqli_error($link));
		header('Content-Type: application/json');
		echo json_encode($errorList);
	}
	
	//Закрываем соедение с сервером
	mysqli_close($link);		
?>

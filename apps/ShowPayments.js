var app = angular.module("ShowPayments", []);

app.controller("formsCtrl", function($scope, $http){

	//Список возможных валют, месяцев, годов
	$scope.currencies = [ "RUB", "USD" ];
	$scope.months = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"];
	$scope.years = [];

	//Определяем диапазон годов срока действия
	//Сделаем период с текущего года + 10 лет	
	var today = new Date();

	for(var i = today.getFullYear(); i < today.getFullYear() + 10; i++) {
		$scope.years.push(i.toString());
	}

	$scope.orderCurrency = "RUB";
	$scope.expirationMonth = "1";
	$scope.expirationYear = today.getFullYear().toString();
	
	//Сообщения для вывода
	$scope.messages = {
		orderNumberRequired: "Нужно заполнить номер заказа",
		orderNumberPattern: "Номер заказа должен содержать от 1 до 10 цифр и не начинаться с 0",
		orderPriceRequired: "Нужно заполнить стоимость заказа",
		orderPricePattern: "Стоимость заказа должна быть в формате 0.00",		
		cardNumberRequired: "Нужно заполнить номер карты",
		cardNumberPattern: "Номер карты должен содержать 16 цифр",
		firstNameRequired: "Нужно заполнить имя владельца",
		firstNamePattern: "Имя владельца должно содержать только латинские буквы",
		lastNameRequired: "Нужно заполнить фамилию владельца",
		lastNamePattern: "Фамилия владельца должна содержать только латинские буквы",
		sendServerError: "Ошибка при отправке запроса серверу",
		dataNotFound: "Данные не найдены",
		successSaveDb: "Изменения успешно сохранены"
	}

	//Выбранные из БД строки
	$scope.selectedRows = { 
		rows: [],
		selected: {}
	};
    
	//Получаем шаблон, таблица на просмотр или на редактирование
    $scope.getTemplate = function (row) {
        if (row.id === $scope.selectedRows.selected.id) return "angularjs/templates/edit.html";
        else return "angularjs/templates/display.html";
    };

    //Обработка кнопки редактировать
    $scope.editRow = function (row) {	
        $scope.selectedRows.selected = angular.copy(row);
    };

    //Сохранить изменения
    $scope.saveRow = function (index) {    	

    	//Если строка не изменилась, то сохранять в БД не будем
    	if(rowEquals($scope.selectedRows.rows[index], $scope.selectedRows.selected)) {
    		$scope.reset();
    		return;	
    	}

    	//Пытаемся сохранить строку в БД
    	saveRowsDb($scope.selectedRows.selected, index);   	  	        
    };

    //Отмена изменений
    $scope.reset = function () {
        $scope.selectedRows.selected = {};
    };

	//Выборка данных
	$scope.showPayments = function() {	
		
		var data = {
			"orderNumber": $scope.orderNumber,
			"orderCurrency": $scope.orderCurrency,
			"cardNumber": $scope.cardNumber,
			"firstName": $scope.firstName,
			"lastName": $scope.lastName,
		};		

		//При новой выборке очищаем сообщения и данные таблицы
		$(".error").remove();
		$scope.selectedRows.rows = [];
		$scope.selectedRows.selected = {};

		$http.post("backend/selectPayments.php", data)
		.success(function(data){
			switch(typeof data) {											
				case "object":
					if (typeof data === "object") {
						//Выводим сообщения об ошибках
						if(data[0] === "error") {
							for(var i = 1; i < data.length; i++) {
								var element = "<p class='error'>" + data[i] + "</p>";
								$(".select-messages").append(element);
							}	
						} else if(data.dataTable.length !== 0) {
							//Выводим таблицу											
							$scope.selectedRows.rows = data.dataTable;				
						} else if(data.dataTable.length === 0) {
							//Данные не найдены
							var element = "<p class='error'>" + $scope.messages.dataNotFound + "</p>";
							$(".table-data").append(element);
						}			
					}
					break;
				//Выводим любые другие сообщения
				case "string":
					var element = "<p class='error'>" + data + "</p>";
					$(".select-messages").append(element);
					break;
			}					
		})
		.error(function(){			
			var element = "<p class='error'>" + $scope.messages.sendServerError + "</p>";
			$(".select-messages").append(element);
		});
	};

	//Сравниваем строки, есть изменения или нет
	function rowEquals(firstRow, secondRow) {

		if(firstRow.id !== secondRow.id) {
			return false;
		}
		if(firstRow.number_order !== secondRow.number_order) {
			return false;
		}
		if(firstRow.price_order !== secondRow.price_order) {
			return false;
		}
		if(firstRow.currency !== secondRow.currency) {
			return false;
		}
		if(firstRow.card_number !== secondRow.card_number) {
			return false;
		}
		if(firstRow.expiration_month !== secondRow.expiration_month) {
			return false;
		}
		if(firstRow.expiration_year !== secondRow.expiration_year) {
			return false;
		}
		if(firstRow.first_name !== secondRow.first_name) {
			return false;
		}
		if(firstRow.last_name !== secondRow.last_name) {
			return false;
		}		
		return true;
	}

	//Сохранение редактируемых строк в БД
	function saveRowsDb(row, index) {
		var editSuccess = false;

		//Если цена только 0, то переводи ее в 0.00
		var regExp = new RegExp("^[0]*$");
		if(regExp.test(row.price_order)) {
			row.price_order = "0.00";
		}

		//Так же если только 0 перед точкой, то переводим в 0.00
		regExp = new RegExp("^[0]*(\\.\\d{2})$");
		if(regExp.test(row.price_order)) {
			row.price_order = "0.00";
		}				
		
		var data = {

	    	"id": row.id,
			"orderNumber": row.number_order,
			"orderPrice": row.price_order,	
			"orderCurrency": row.currency,
			"cardNumber": row.card_number,
			"expirationMonth": row.expiration_month,
			"expirationYear": row.expiration_year,
			"firstName": row.first_name,
			"lastName": row.last_name,			
		}

		$http.post("backend/saveEditRow.php", data)
		.success(function(data){
			switch(typeof data) {											
				case "object":
					if (typeof data === "object") {
						//Выводим сообщения об ошибках
						if(data[0] === "error") {
							for(var i = 1; i < data.length; i++) {
								var element = "<p class='error'>" + data[i] + "</p>";
								$(".edit-messages").append(element);
							}	
						} else if(data[0] === "success") {									
							editSuccess = true;
						} 			
					}
					break;
				//Выводим любые другие сообщения
				case "string":
					var element = "<p class='error'>" + data + "</p>";
					$(".select-messages").append(element);
					break;
			}
		})
		.error(function(){
			var element = "<p class='error'>" + $scope.messages.sendServerError + "</p>";
			$(".edit-messages").append(element);
		})
		.then(function(){
			//После выполнения запроса к серверу, проверяем, если успешно сохранилось в БД
			//Применяем изменения и на экране
    		if(editSuccess) {
    			$scope.selectedRows.rows[index] = angular.copy($scope.selectedRows.selected);
    			$scope.reset();
    			return;
    		}
    		$scope.reset();			
		}) 
	}


});
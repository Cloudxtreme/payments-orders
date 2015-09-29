var app = angular.module("ShowPayments", []);

app.controller("selectFormCtrl", function($scope, $http){

	$scope.currencies = [ "RUB", "USD" ];
	$scope.months = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"];

	//Определяем диапазон годов срока действия
	//Сделаем период с текущего года + 10 лет
	$scope.years = [];

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
		dataNotFound: "Данные не найдены"
	}

	$scope.selectedRows = { 
		rows: [],
		selected: {}
	};
    
	//Получаем шаблон, таблица на просмотр или на редактирование
    $scope.getTemplate = function (row) {
        if (row.id === $scope.selectedRows.selected.id) return "edit";
        else return "display";
    };

    //Обработка кнопки редактировать
    $scope.editRow = function (row) {	
        $scope.selectedRows.selected = angular.copy(row);
    };

    //Сохранить изменения
    $scope.saveRow = function (index) {
        $scope.selectedRows.rows[index] = angular.copy($scope.selectedRows.selected);
        $scope.reset();
    };

    //Отмена изменений
    $scope.reset = function () {
        $scope.selectedRows.selected = {};
    };

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

		$http.post("selectPayments.php", data)
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
});
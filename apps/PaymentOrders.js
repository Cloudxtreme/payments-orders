var app = angular.module("PaymentOrders", []);

app.controller("formCtrl", function($scope, $http, $timeout){

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
		cvvCodeRequired: "Нужно заполнить CVV-код",
		cvvCodePattern: "CVV-код должен содержать 3 цифры",
		sendServerError: "Ошибка при отправке запроса серверу"
	}

	//Сохраняем оплаты
	$scope.savePayments = function() {	
		
		//Если цена только 0, то переводи ее в 0.00
		var regExp = new RegExp("^[0]*$");
		if(regExp.test($scope.orderPrice)) {
			$scope.orderPrice = "0.00";
		}

		//Так же если только 0 перед точкой, то переводим в 0.00
		regExp = new RegExp("^[0]*(\\.\\d{2})$");
		if(regExp.test($scope.orderPrice)) {
			$scope.orderPrice = "0.00";
		}		

		//Данные для передачи на сервер
		var data = {
			"orderNumber": $scope.orderNumber,
			"orderPrice": $scope.orderPrice,	
			"orderCurrency": $scope.orderCurrency,
			"cardNumber": $scope.cardNumber,
			"expirationMonth": $scope.expirationMonth,
			"expirationYear": $scope.expirationYear,
			"firstName": $scope.firstName,
			"lastName": $scope.lastName,
			"cvvCode": $scope.cvvCode,
		};		

		//При сохранении убираем сообщения 
		$(".error").remove();
		$(".success").remove();

		//Делаем запрос на сервер
		$http.post("backend/savePayment.php", data)
		.success(function(data){	
			switch(typeof data) {
				case "object":
					//Выводим сообщения об ошибках
					if(data[0] === "error") {
						for(var i = 1; i < data.length; i++) {
							var element = "<p class='error'>" + data[i] + "</p>";
							$(".card-messages").append(element);
						}	
					} else if (data[0] === "success") {
						//Выводим сообщение при успешном сохранении
						var element = "<p class='success'>" + data[1] + "</p>";
						$(".card-messages").append(element);
						$timeout(function() {
							$(".success").remove();
						}, 2000);
					}			
					break;				
				//Выводим любые другие сообщения
				case "string":
					var element = "<p class='error'>" + data + "</p>";
					$(".card-messages").append(element);
					break;				
			}					
		})
		.error(function(){			
			//Ошибки при отправке запроса
			var element = "<p class='error'>" + $scope.messages.sendServerError + "</p>";
			$(".card-messages").append(element);
		});
	};
});
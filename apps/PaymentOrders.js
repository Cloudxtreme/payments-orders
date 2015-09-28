var app = angular.module("PaymentOrders", []);

app.controller("formCtrl", function($scope, $http){

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
		firstNamePattern: "Имя владельца должна содержать только латинские буквы",
		lastNameRequired: "Нужно заполнить фамилию владельца",
		lastNamePattern: "Фамилия владельца должна содержать только латинские буквы",
		cvvCodeRequired: "Нужно заполнить CVV-код",
		cvvCodePattern: "CVV-код должен содержать 3 цифры",
		sendServerError: "Ошибка при отправке запроса серверу"
	}

	$scope.savePayments = function() {	
		
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

		$http.post("savePayment.php", data)
		.success(function(data){	
			switch(typeof data) {
				//Выводим сообщения об ошибках
				case "object":
					if (typeof data === "object") {
						for(var i = 0; i < data.length; i++) {
							var element = "<p class='error'>" + data[i] + "</p>";
							$(".card-messages").append(element)
						}				
					}
					break;
				//Выводим сообщение при успешном сохранении
				case "string":
					var element = "<p class='success'>" + data + "</p>";
					$(".card-messages").append(element)
					break;
			}					
		})
		.error(function(){			
			var element = "<p class='error'>" + $scope.messages.sendServerError + "</p>";
			$(".card-messages").append(element)
		})
	}

});
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

	$scope.savePayments = function() {
	$scope.response;	
		
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

		$http.post("savePayment.php", 	data)
		.success(function(data){
			console.log(typeof data);
			console.log(data);
		})
		.error(function(){

		})
	}

});
<!DOCTYPE html>
<html>
	<head>
		<title>Оплата заказа</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="styles/reset.css">
		<link rel="stylesheet" type="text/css" href="styles/bootstrap/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="styles/main.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<script type="text/javascript" src="angularjs/angular.min.js"></script>
		<script type="text/javascript" src="apps/app.js"></script>
	</head>
	<body ng-app="PaymentOrders">
		<!-- <form action="savePayment.php" method="POST"> -->
		<form ng-controller="formCtrl">	
<!-- Данные заказа -->
			<div class="container-fluid">
				<div class="order-data"> 
					<div class="form-group">																
						<div class="row">
							<div class="col col-xs-4">
								<label for="order-number">Номер заказа</label>
								<input 	class="form-control" id="order-number" name="order-number" type="text" size="10"
							 			placeholder="0" maxlength="10" ng-model="orderNumber"> <!-- required pattern="[0-9]{1,10}" -->
							</div>
							<div class="col col-xs-4">
								<label for="order-price">Стоимость заказа</label>
								<input 	class="form-control" id="order-price" name="order-price" type="text" size="10"  
										placeholder="0.00" maxlength="10" ng-model="orderPrice"> <!-- required pattern="\d+(\.\d{2})?" -->
							</div>
							<div class="col col-xs-4">
								<label for="order-currency">Валюта</label>
								<select class="form-control" id="order-currency" name="order-currency" ngModel="orderCurrency">
									<option ng-repeat="currency in currencies" value="{{currency}}">{{currency}}</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
<!-- Карта - передняя сторона -->
			<div class="container-fluid">
				<div class="card-front">
					<!-- Номер карты -->			
					<div class="form-group">
						<label for="card-number">Номер карты</label>
						<div class="row">
							<div class="col col-xs-6">
								<input 	id="card-number" name="card-number" class="form-control" type="text" size="16" 
										placeholder="0000000000000000" maxlength="16" ng-model="cardNumber">	<!-- required pattern="[0-9]{16}" -->		
							</div>												
						</div>	
						<i class="fa fa-cc-visa"></i>
						<i class="fa fa-cc-mastercard"></i>
					</div> 
					<!-- Срок действия -->			
					<div class="form-group">
						<label for="expiration-month">Срок действия</label>
						<div class="row">
							<div class="col col-xs-3">
								<select name="expiration-month" id="expiration-month" class="form-control" ng-model="expirationMonth"> 
									<option ng-repeat="month in months" value="{{month}}">{{month}}</option>
								</select>		
							</div>
							<div class="col col-xs-3">
								<select name="expiration-year" id="expiration-year" class="form-control" ng-model="expirationYear"> 
									<option ng-repeat="year in years" value="{{year}}">{{year}}</option>
								</select>								
							</div>
						</div>
					</div>
					<!-- Имя владельца -->
					<div class="form-group">									
						<div class="row">							
							<div class="col col-xs-6">				
								<label for="first-name">Имя владельца</label>			
								<input 	name="first-name" id="first-name" class="form-control" type="text" 
										size="18" placeholder="Ivan" maxlength="18" ng-model="firstName"> <!-- pattern="^[a-zA-Z]+$" required -->
							</div>
							<div class="col col-xs-6">										
								<label for="last-name">Фамилия владельца</label>
								<input 	id="last-name" name="last-name"  class="form-control" type="text" 
										size="18" placeholder="Ivanov" maxlength="18" ng-model="lastName"> <!-- pattern="^[a-zA-Z]+$" required -->
							</div>			
						</div>		
					</div>						
		 		</div>	 			 		
	 		</div>	
<!-- Карта - задняя сторона  -->	 		
	 		<div class="card-back">
	 			<div class="black-line">
	 			</div>	 			
				<!-- Код CVV  -->
	 			<div class="form-group">
	 				<label for="cvv-code">CVV</label>
	 				<div class="form-inline">
	 					<input 	name="cvv-code" id-"cvv-code" class="form-control" type="password" size="3" 
	 							placeholder="000" maxlength="3" ng-model="cvvCode"> <!-- pattern="[0-9]{3}" required -->				
	 				</div>
	 				<span class="cvv-definition">Последние 3&nbsp;цифры на&nbsp;обратной стороне карты</span>			
	 			</div>			
			</div>				
<!-- Кнопки -->
	 		<div class="buttons">

					<button type="button" class="btn btn-default" ng-click="savePayments()">Оплатить</button>
					<button type="button" class="btn btn-default">Платежи</button>

			</div>	
		</form>
	</body>
</html>
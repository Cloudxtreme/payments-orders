<!DOCTYPE html>
<html>
	<head>
		<title>Оплата заказа</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="styles/reset.css">
		<link rel="stylesheet" type="text/css" href="styles/bootstrap/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="styles/index.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<script type="text/javascript" src="angularjs/angular.min.js"></script>
		<script type="text/javascript" src="jQuery/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="apps/PaymentOrders.js"></script>
	</head>
	<body ng-app="PaymentOrders">
		<form ng-submit="savePayments()" ng-controller="formCtrl" name="paymentsForm" novalidate>	
<!-- Данные заказа -->
			<div class="container-fluid">
				<p>1. Заполните параметры заказа</p>
				<div class="order-data">					
					<div class="form-group"> 												
						<div class="row">
							<div class="col col-xs-4" ng-class="{ 'has-error' :  paymentsForm.orderNumber.$error.required && !paymentsForm.orderNumber.$pristine || paymentsForm.orderNumber.$error.pattern }" >
								<label for="order-number">Номер заказа</label>
								<input 	class="form-control" id="order-number" name="orderNumber" type="text"
								 		size="10" placeholder="0" maxlength="10" ng-model="orderNumber" 
								 		required ng-pattern="/^[1-9][0-9]{0,9}$/">
							</div>
							<div class="col col-xs-4" ng-class="{ 'has-error' :  paymentsForm.orderPrice.$error.required && !paymentsForm.orderPrice.$pristine || paymentsForm.orderPrice.$error.pattern }">
								<label for="order-price">Стоимость заказа</label>
								<input 	class="form-control" id="order-price" name="orderPrice" type="text" 
										size="10" placeholder="0.00" maxlength="10" ng-model="orderPrice" required ng-pattern="/^\d+(\.\d{2})?$/">  
							</div>
							<div class="col col-xs-4">
								<label for="order-currency">Валюта</label>
								<select class="form-control" id="order-currency" name="order-currency" 
										ng-model="orderCurrency">
									<option ng-repeat="currency in currencies" value="{{currency}}">
										{{currency}}
									</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="order-messages">
					<p ng-show="paymentsForm.orderNumber.$error.required && !paymentsForm.orderNumber.$pristine" class="client-error">
						{{messages.orderNumberRequired}}
					</p>
					<p ng-show="paymentsForm.orderNumber.$error.pattern" class="client-error">
						{{messages.orderNumberPattern}}
					</p>
					<p ng-show="paymentsForm.orderPrice.$error.required && !paymentsForm.orderPrice.$pristine" class="client-error">
						{{messages.orderPriceRequired}}
					</p>
					<p ng-show="paymentsForm.orderPrice.$error.pattern" class="client-error">
						{{messages.orderPricePattern}}
					</p>							
				<div/>
<!-- Карта - передняя сторона -->		
				<p>2. Введите данные банковской карты</p>
				<div class="card">								
					<div class="card-front">					
						<!-- Номер карты -->			
						<div class="form-group">
							<label for="card-number">Номер карты</label>
							<div class="row">
								<div class="col col-xs-6" ng-class="{ 'has-error' :  paymentsForm.cardNumber.$error.required && !paymentsForm.cardNumber.$pristine || paymentsForm.cardNumber.$error.pattern }">
									<input 	id="card-number" name="cardNumber" class="form-control" type="text" 		size="16" placeholder="0000000000000000" maxlength="16" 
											ng-model="cardNumber" required ng-pattern="/[0-9]{16}/" >
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
									<select name="expiration-month" id="expiration-month" class="form-control" 	
											ng-model="expirationMonth"> 
										<option ng-repeat="month in months" value="{{month}}">{{month}}</option>
									</select>		
								</div>
								<div class="col col-xs-3">
									<select name="expiration-year" id="expiration-year" class="form-control" 
											ng-model="expirationYear"> 
										<option ng-repeat="year in years" value="{{year}}">{{year}}</option>
									</select>								
								</div>
							</div>
						</div>
						<!-- Имя владельца -->
						<div class="form-group">									
							<div class="row">							
								<div class="col col-xs-6" ng-class="{ 'has-error' :  paymentsForm.firstName.$error.required && !paymentsForm.firstName.$pristine || paymentsForm.firstName.$error.pattern }">				
									<label for="first-name">Имя владельца</label>			
									<input 	name="firstName" id="first-name" class="form-control" type="text" 
											size="18" placeholder="Ivan" maxlength="18" ng-model="firstName" required ng-pattern="/^[A-Za-z]+$/"> 
								</div>
								<div class="col col-xs-6" ng-class="{ 'has-error' :  paymentsForm.lastName.$error.required && !paymentsForm.lastName.$pristine || paymentsForm.lastName.$error.pattern }">										
									<label for="last-name">Фамилия владельца</label>
									<input 	id="last-name" name="lastName"  class="form-control" type="text" 
											size="18" placeholder="Ivanov" maxlength="18" ng-model="lastName" required ng-pattern="/^[A-Za-z]+$/">
								</div>			
							</div>		
						</div>						
			 		</div>	 			 		
<!-- Карта - задняя сторона  -->	 		
			 		<div class="card-back">
			 			<div class="black-line"></div>	 			
						<!-- Код CVV  -->
			 			<div class="form-group">
			 				<label for="cvv-code">CVV</label>
			 				<div class="form-inline" ng-class="{ 'has-error' :  paymentsForm.cvvCode.$error.required && !paymentsForm.cvvCode.$pristine || paymentsForm.cvvCode.$error.pattern }">
			 					<input 	name="cvvCode" id="cvv-code" class="form-control" type="password" 
			 							size="3" placeholder="000" maxlength="3" ng-model="cvvCode" required 
			 							ng-pattern="/[0-9]{3}/"> 
			 				</div>
			 				<span class="cvv-definition">
			 					Последние 3&nbsp;цифры на&nbsp;обратной стороне карты
			 				</span>			
			 			</div>			
					</div>	
				</div>
			</div>			
<!-- Кнопки -->
	 		<div class="buttons">
				<button type="submit" class="btn btn-default" ng-disabled="paymentsForm.$invalid">
					Оплатить
				</button>
				<a class="btn btn-default" href="./showPayments.php" role="button">
					Платежи
				</a>	
			</div>	
<!-- Сообщения -->
				<div class="card-messages">
					<p ng-show="paymentsForm.cardNumber.$error.required && !paymentsForm.cardNumber.$pristine" class="client-error">
						{{messages.cardNumberRequired}}
					</p>
					<p ng-show="paymentsForm.cardNumber.$error.pattern" class="client-error">
						{{messages.cardNumberPattern}}
					</p>
					<p ng-show="paymentsForm.firstName.$error.required && !paymentsForm.firstName.$pristine" class="client-error">
						{{messages.firstNameRequired}}
					</p>
					<p ng-show="paymentsForm.firstName.$error.pattern" class="client-error">
						{{messages.firstNamePattern}}
					</p>				
					<p ng-show="paymentsForm.lastName.$error.required && !paymentsForm.lastName.$pristine" class="client-error">
						{{messages.lastNameRequired}}
					</p>
					<p ng-show="paymentsForm.lastName.$error.pattern" class="client-error">
						{{messages.lastNamePattern}}
					</p>		
					<p ng-show="paymentsForm.cvvCode.$error.required && !paymentsForm.cvvCode.$pristine" class="client-error">
						{{messages.cvvCodeRequired}}
					</p>
					<p ng-show="paymentsForm.cvvCode.$error.pattern" class="client-error">
						{{messages.cvvCodePattern}}
					</p>						
				</div>
			</div>
		</form>
	</body>
</html>
<!DOCTYPE html>
<html>
	<head>
		<title>Просмотр платежей</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="styles/reset.css">
		<link rel="stylesheet" type="text/css" href="styles/bootstrap/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="styles/showPayments.css">
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> -->
		<script type="text/javascript" src="angularjs/angular.min.js"></script>
		<script type="text/javascript" src="jQuery/jquery-2.1.4.min.js"></script>
	</head>
	<body>
		<!-- Критерии для выбора данных -->
		<section class="for-select">			
			<form action="selectPaymetns.php" method="POST">
				<div class="container-fluid">
					<p>Заполните параметры для выбора данных</p>
					<div class="data-for-select">
						<div class="form-group"> 										
							<div class="row">
								<div class="col col-xs-2">
									<label for="order-number">Номер заказа</label>
									<input 	class="form-control" id="order-number" name="orderNumber" type="text"
									 		size="10" placeholder="0" maxlength="10" ng-model="orderNumber" >
								</div>
								<div class="col col-xs-1">
									<label for="order-currency">Валюта</label>
									<select class="form-control" id="order-currency" name="orderСurrency" 
											ngModel="orderCurrency">
										<option ng-repeat="currency in currencies" value="{{currency}}">
											{{currency}}
										</option>
									</select>
								</div>								
								<div class="col col-xs-3">
									<label for="card-number">Номер карты<sup>*</sup></label>
									<input 	id="card-number" name="cardNumber" class="form-control" type="text" 		
											size="16" placeholder="0000000000000000" maxlength="16" 
											ng-model="cardNumber">
								</div>
								<div class="col col-xs-3">				
									<label for="first-name">Имя владельца<sup>*</sup></label>			
									<input 	name="firstName" id="first-name" class="form-control" type="text" 
											size="18" placeholder="Ivan" maxlength="18" ng-model="firstName"> 
								</div>
								<div class="col col-xs-3">										
									<label for="last-name">Фамилия владельца<sup>*</sup></label>
									<input 	id="last-name" name="lastName"  class="form-control" type="text" 
											size="18" placeholder="Ivanov" maxlength="18" ng-model="lastName">
								</div>											
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-default">Показать платежи</button>
				</div>
			</form>
		</section>
		<!-- Таблица с данными -->
		<section class="selected-data">
			<form>
			</form>
		</section>
	</body>
</html>
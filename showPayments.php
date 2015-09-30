<!DOCTYPE html>
<html>
	<head>
		<title>Просмотр платежей</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="styles/reset.css">
		<link rel="stylesheet" type="text/css" href="styles/bootstrap/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="styles/showPayments.css">
		<script type="text/javascript" src="angularjs/angular.min.js"></script>
		<script type="text/javascript" src="jQuery/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="apps/ShowPayments.js"></script>
	</head>
	<body ng-app="ShowPayments" ng-controller="formsCtrl">
		<!-- Критерии для выбора данных -->
		<section class="for-select">			
			<form ng-submit="showPayments()" name="selectForm" novalidate>
				<div class="container-fluid">
					<p>Заполните параметры для выбора данных (<span class="red">*</span> - поля, обязательные для заполнения)</p>
					<div class="data-for-select">
						<div class="form-group"> 										
							<div class="row">
								<div class="col col-xs-2" ng-class="{ 'has-error' : selectForm.orderNumber.$error.pattern }" >
								<label for="order-number">Номер заказа</label>
								<input 	class="form-control" id="order-number" name="orderNumber" type="text"
								 		size="10" placeholder="0" maxlength="10" ng-model="orderNumber" 
								 		ng-pattern="/^[1-9][0-9]{0,9}$/">
								</div>
								<div class="col col-xs-1">
									<label for="order-currency">Валюта</label>
									<select class="form-control" id="order-currency" name="orderСurrency" 
											ng-model="orderCurrency">
										<option ng-repeat="currency in currencies" value="{{currency}}">
											{{currency}}
										</option>
									</select>
								</div>								
								<div class="col col-xs-3" ng-class="{ 'has-error' :  selectForm.cardNumber.$error.required && !selectForm.cardNumber.$pristine || selectForm.cardNumber.$error.pattern }">
									<label for="card-number">Номер карты<sup>*</sup></label>
									<input 	id="card-number" name="cardNumber" class="form-control" type="text"	size="16" placeholder="0000000000000000" maxlength="16" 
											ng-model="cardNumber" required ng-pattern="/[0-9]{16}/" >
									
								</div>
								<div class="col col-xs-3" ng-class="{ 'has-error' :  selectForm.firstName.$error.required && !selectForm.firstName.$pristine || selectForm.firstName.$error.pattern }">				
									<label for="first-name">Имя владельца<sup>*</sup></label>			
									<input 	name="firstName" id="first-name" class="form-control" type="text" 
											size="18" placeholder="Ivan" maxlength="18" ng-model="firstName" required ng-pattern="/^[A-Za-z]+$/"> 
								</div>
								<div class="col col-xs-3" ng-class="{ 'has-error' :  selectForm.lastName.$error.required && !selectForm.lastName.$pristine || selectForm.lastName.$error.pattern }">										
									<label for="last-name">Фамилия владельца<sup>*</sup></label>
									<input 	id="last-name" name="lastName"  class="form-control" type="text" 
											size="18" placeholder="Ivanov" maxlength="18" ng-model="lastName" required ng-pattern="/^[A-Za-z]+$/">
								</div>											
							</div>
						</div>
					</div>
					<div class="button-show-payments">
						<button type="submit" class="btn btn-default" ng-disabled="selectForm.$invalid">Показать платежи</button>
					</div>	
					<!-- Сообщения критериев выбора -->
					<div class="select-messages">
						<p ng-show="selectForm.orderNumber.$error.pattern" class="client-error">
							{{messages.orderNumberPattern}}
						</p>
						<p ng-show="selectForm.cardNumber.$error.required && !selectForm.cardNumber.$pristine" class="client-error">
							{{messages.cardNumberRequired}}
						</p>
						<p ng-show="selectForm.cardNumber.$error.pattern" class="client-error">
							{{messages.cardNumberPattern}}
						</p>				
						<p ng-show="selectForm.firstName.$error.required && !selectForm.firstName.$pristine" class="client-error">
							{{messages.firstNameRequired}}
						</p>
						<p ng-show="selectForm.firstName.$error.pattern" class="client-error">
							{{messages.firstNamePattern}}
						</p>				
						<p ng-show="selectForm.lastName.$error.required && !selectForm.lastName.$pristine" class="client-error">
							{{messages.lastNameRequired}}
						</p>
						<p ng-show="selectForm.lastName.$error.pattern" class="client-error">
							{{messages.lastNamePattern}}
						</p>		
					</div>					
				</div>
			</form>
		</section>
		<!-- Таблица с данными -->
		<section class="selected-data">
			<form name="editForm" novalidate>
				<div class="container-fluid">
					<div class="table-data">	
						<table class='table table-bordered table-striped' ng-show="selectedRows.rows.length !== 0">
					        <thead>
					        	<th>№</th>		
					        	<th>Номер заказа</th>
								<th>Стоимость заказа</th>
								<th>Валюта</th>
								<th>Номер карты</th>  			
								<th>Месяц срока действия</th>
								<th>Год срока действия</th>
								<th>Имя владельца</th>
								<th>Фамилия владельца</th>
								<th class="header-table-icons"></th>
					        </thead>
					        <tbody>
					        	<!-- Загружаем шаблоны в зависимости от редакитрования или просмотра -->
					            <tr ng-repeat="row in selectedRows.rows" ng-include="getTemplate(row)">
					            </tr>
					        </tbody>
					    </table>				
					</div>
					<!-- Сообщения при редактировании таблицы -->
					<div class="edit-messages">
						<p ng-show="editForm.orderNumber.$error.required && !editForm.orderNumber.$pristine" class="client-error">
							{{messages.orderNumberRequired}}
						</p>
						<p ng-show="editForm.orderNumber.$error.pattern" class="client-error">
							{{messages.orderNumberPattern}}
						</p>	
						<p ng-show="editForm.orderPrice.$error.required && !editForm.orderPrice.$pristine" class="client-error">
							{{messages.orderPriceRequired}}
						</p>
						<p ng-show="editForm.orderPrice.$error.pattern" class="client-error">
							{{messages.orderPricePattern}}
						</p>
						<p ng-show="editForm.cardNumber.$error.required && !editForm.cardNumber.$pristine" class="client-error">
							{{messages.cardNumberRequired}}
						</p>
						<p ng-show="editForm.cardNumber.$error.pattern" class="client-error">
							{{messages.cardNumberPattern}}
						</p>
						<p ng-show="editForm.firstName.$error.required && !editForm.firstName.$pristine" class="client-error">
							{{messages.firstNameRequired}}
						</p>
						<p ng-show="editForm.firstName.$error.pattern" class="client-error">
							{{messages.firstNamePattern}}
						</p>				
						<p ng-show="editForm.lastName.$error.required && !editForm.lastName.$pristine" class="client-error">
							{{messages.lastNameRequired}}
						</p>
						<p ng-show="editForm.lastName.$error.pattern" class="client-error">
							{{messages.lastNamePattern}}
						</p>																	
					</div>
				</div>
			</form>
		</section>
	</body>
</html>
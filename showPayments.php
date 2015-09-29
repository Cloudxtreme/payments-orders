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
	<body ng-app="ShowPayments" ng-controller="selectFormCtrl">
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
								<th><span class='glyphicon glyphicon-pencil'></span></th>
								<th><span class='glyphicon glyphicon-floppy-disk'></span></th>
								<th><span class='glyphicon glyphicon-remove'></span></th>
					        </thead>
					        <tbody>
					            <tr ng-repeat="row in selectedRows.rows" ng-include src="getTemplate(row)">
					            </tr>
					        </tbody>
					    </table>				
					</div>
					<div class=".edit-messages">
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
		<script type="text/ng-template" id="display">
	    	<td>{{$index+1}}</td>
	    	<td>{{row.number_order}}</td>
	    	<td>{{row.price_order}}</td>
	    	<td>{{row.currency}}</td>				    	
	    	<td>{{row.card_number}}</td>
	    	<td>{{row.expiration_month}}</td>
	    	<td>{{row.expiration_year}}</td>
	    	<td>{{row.first_name}}</td>
	    	<td>{{row.last_name}}</td>
	    	<td>
	    		<a href='#' ng-click="editRow(row)">
	    			<span class='glyphicon glyphicon-pencil'></span>
	    		</a>
	    	</td>		
	    	<td>
	    		<span class='glyphicon glyphicon-floppy-disk'></span>
	    	</td>
	    	<td>
	    		<span class='glyphicon glyphicon-remove'></span>
	    	</td>		    					    					    	
	    </script>
	    <script type="text/ng-template" id="edit">
	    	
	    	<td>{{$index+1}}</td>
	    	
	    	<td ng-class="{ 'has-error' :  editForm.orderNumber.$error.required && !editForm.orderNumber.$pristine || editForm.orderNumber.$error.pattern }">
	    		<input class="form-control" id="order-number" name="orderNumber" type="text"
						size="10" placeholder="0" maxlength="10" ng-model="selectedRows.selected.number_order" 
						required ng-pattern="/^[1-9][0-9]{0,9}$/">
			</td>
	    	
	    	<td ng-class="{ 'has-error' :  editForm.orderPrice.$error.required && !editForm.orderPrice.$pristine || editForm.orderPrice.$error.pattern }">
	    		<input class="form-control" id="order-price" name="orderPrice" type="text" 
						size="10" placeholder="0.00" maxlength="10" ng-model="selectedRows.selected.price_order" 
						required ng-pattern="/^\d+(\.\d{2})?$/">
			</td>
	    	
	    	<td>
	    		<select class="form-control" id="order-currency" name="order-currency" 
						ng-model="selectedRows.selected.currency">
					<option ng-repeat="currency in currencies" value="{{currency}}">
						{{currency}}
					</option>
				</select>
			</td>				    	
	    	
	    	<td ng-class="{ 'has-error' :  editForm.cardNumber.$error.required && !editForm.cardNumber.$pristine || editForm.cardNumber.$error.pattern }">
	    		<input 	id="card-number" name="cardNumber" class="form-control" type="text"
	    		 		size="16" placeholder="0000000000000000" maxlength="16" 
						ng-model="selectedRows.selected.card_number" required ng-pattern="/[0-9]{16}/">
			</td>
	    	
	    	<td>
	    		<select name="expiration-month" id="expiration-month" class="form-control" 	
						ng-model="selectedRows.selected.expiration_month"> 
					<option ng-repeat="month in months" value="{{month}}">
						{{month}}
					</option>
				</select>
			</td>

	    	<td>
	    		<select name="expiration-year" id="expiration-year" class="form-control" 
	    				ng-model="selectedRows.selected.expiration_year"> 
					<option ng-repeat="year in years" value="{{year}}">
						{{year}}
					</option>
				</select>
			</td>

	    	<td ng-class="{ 'has-error' :  editForm.firstName.$error.required && !editForm.firstName.$pristine || editForm.firstName.$error.pattern }">
	    		<input 	name="firstName" id="first-name" class="form-control" type="text" 
						size="18" placeholder="Ivan" maxlength="18" ng-model="selectedRows.selected.first_name" 
						required ng-pattern="/^[A-Za-z]+$/"> 
			</td>

	    	<td ng-class="{ 'has-error' :  editForm.lastName.$error.required && !editForm.lastName.$pristine || editForm.lastName.$error.pattern }">
	    		<input 	id="last-name" name="lastName"  class="form-control" type="text" 
						size="18" placeholder="Ivanov" maxlength="18" ng-model="selectedRows.selected.last_name" 
						required ng-pattern="/^[A-Za-z]+$/">
			</td>

	    	<td>
	    		<span class='glyphicon glyphicon-pencil'></span>	    		
    		</td>				    					    	
    		<td>
    			<a href='#' ng-click="saveRow($index)" ng-show="!editForm.$invalid">
	    			<span class='glyphicon glyphicon-floppy-disk'></span>
	    		</a>
    		</td>
    		<td>
    		    <a href='#' ng-click="reset()">
	    			<span class='glyphicon glyphicon-remove'></span>
	    		</a>
    		</td>
	    </script>	
	</body>
</html>
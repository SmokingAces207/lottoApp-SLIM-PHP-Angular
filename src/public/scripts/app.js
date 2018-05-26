'use strict';
var myApp = angular.module('myApp', ["ngRoute"]);

myApp.config(function($routeProvider) {
	$routeProvider
		.when("/", {
			templateUrl : "views/main.html",
			controller : "mainController"
		})
		.when("/createticket", {
			templateUrl : "<h1>TESTING...CREATE A TICKET!!!</h1>",
			controller : "ticketController"
		})
//		.when("/tickets", {
//			templateUrl : "tickets.html"
//		})
		.otherwise({ redirectTo: '/' });   
});

myApp.controller('mainController', ['$scope', '$filter', '$timeout', function ($scope, $filter, $http) {
    
    $scope.number_of_lines = 6;
    
    $scope.createNewTicket = function() {
        // $http.get("tickets.php").then(function(response) {
        //     $scope.data = response.data;
        // });
        $http({
            method: 'POST',
            url: '../routes/tickets.php',
            data: {
                number_of_lines: $scope.number_of_lines
            }
        }).then(function (response) {
            $scope.ticket = response;
            console.log("response");
            console.log($scope.ticket);
        }),function (error) {
            console.log("error");
            console.log(error);
        }
        console.log("create ticket");
        console.log($scope.data);
    }
    
    $scope.viewTickets = function() {
        $http({
            method: 'GET',
            url: '/ticket'
            //data: $scope.id
        }).then(function (response) {
            $scope.tickets = response;
            console.log("response");
            console.log($scope.tickets);
        }),function (error) {
            console.log("error");
            console.log(error);
        }
        console.log("view tickets");
    }

    $scope.viewTickets();
    
//    $scope.postData = function() {
//        $http({
//            method: "POST",
//            url: "routes/tickets.php",
//            data: $scope.number_of_lines
//        }).success(function(data) {
//            
//        });
//    }
}]);

myApp.controller('ticketController', ['$scope', function ($scope) {
    
    console.log("TicketController...");
}]);
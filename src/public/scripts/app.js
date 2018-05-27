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
		.when("/viewtickets", {
            templateUrl : "views/tickets.html",
            controller : "ticketController"
        })
		.otherwise({ redirectTo: '/' });   
});

myApp.controller('mainController', ['$scope', '$http', '$location', function ($scope, $http, $location) {
    
    $scope.number_of_lines = 6;
    
    $scope.createNewTicket = function() {
        $http({
            method: 'POST',
            url: '/ticket',
            data: {
                number_of_lines: angular.fromJson($scope.number_of_lines)
            }
        }).then(function (response) {
            $scope.ticket = response;
            console.log(response);
        }),function (error) {
            console.log(error);
        }
    }
    
    $scope.viewTickets = function($path) {
        $location.url($path);
    }
    
}]);

myApp.controller('ticketController', ['$scope', '$http', '$location', function ($scope, $http, $location) {

    $scope.displayTickets = true;
    $scope.displayLines = false;
    
    $scope.goHome = function($path) {
        $scope.getTickets();
        console.log($location.path());
        $location.url($path);
    }

    $scope.swapViews = function() {
        $scope.displayTickets = !$scope.displayTickets;
        $scope.displayLines = !$scope.displayLines;
    }

    $scope.getTickets = function() {
        $http({
            method: 'GET',
            url: '/ticket'
        }).then(function (response) {
            $scope.tickets = response.data;
            console.log(response);
        }),function (error) {
            console.log(error);
        }
    }

    $scope.openTicket = function($ticket) {
        
        $scope.swapViews();
        console.log($ticket);
        console.log("Ticket Displayed");
    }

    $scope.getTickets();
}]);
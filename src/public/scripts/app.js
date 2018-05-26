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

myApp.controller('mainController', ['$scope', '$filter', '$timeout', '$location', function ($scope, $filter, $http, $location) {
    
    $scope.number_of_lines = 6;
    
    $scope.createNewTicket = function() {
        // $http.get("tickets.php").then(function(response) {
        //     $scope.data = response.data;
        // });
        $http({
            method: 'POST',
            url: '/ticket',
            data: {
                number_of_lines: angular.fromJson($scope.number_of_lines)
            }
        }).then(function (response) {
            $scope.ticket = response;
            console.log("response");
            console.log(response);
        }),function (error) {
            console.log("error");
            console.log(error);
        }
        console.log("create ticket");
        console.log($scope.data);
    }

    $scope.getTickets = function() {

        $http({
            method: 'GET',
            url: '/ticket'
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
    
    $scope.viewTickets = function($path) {
        $scope.getTickets();
        console.log($location.path());
        $location.url($path);
    }
    
}]);

myApp.controller('ticketController', ['$scope', '$location', function ($scope, $location) {

    $scope.displayTickets = true;
    $scope.displayLines = false;

    $scope.tickets = {
        1: { id : "1", number_of_lines : "6"},
        2: { id : "2", number_of_lines : "4"},
        3: { id : "3", number_of_lines : "7"},
        4: { id : "4", number_of_lines : "9"},
        5: { id : "5", number_of_lines : "2"},
        6: { id : "6", number_of_lines : "12"},
        7: { id : "7", number_of_lines : "5"},
        8: { id : "8", number_of_lines : "1"}
    };
    
    $scope.goHome = function($path) {
        $scope.getTickets();
        console.log($location.path());
        $location.url($path);
    }

    $scope.swapViews = function() {
        $scope.displayTickets = !$scope.displayTickets;
        $scope.displayLines = !$scope.displayLines;
    }

    $scope.openTicket = function($ticket) {
        
        $scope.swapViews();
        console.log("Ticket Displayed");
    }
}]);
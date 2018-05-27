'use strict';
var myApp = angular.module('myApp', ["ngRoute"]);

myApp.config(function($routeProvider) {
	$routeProvider
		.when("/", {
			templateUrl : "views/main.html",
			controller : "mainController"
		})
		.when("/createticket", {
			templateUrl : "views/create.html",
			controller : "createController"
		})
        .when("/addlines", {
            templateUrl : "views/addLines.html",
            controller : "addLinesController"
        })
		.when("/viewtickets", {
            templateUrl : "views/tickets.html",
            controller : "ticketController"
        })
		.otherwise({ redirectTo: '/' });   
});

myApp.controller('mainController', ['$scope', '$location', 
    function ($scope, $location) {
    
    $scope.go = function($path) {
        $location.url($path);
    }
    
}]);

myApp.controller('createController', ['$scope', '$http', '$location', '$timeout', 
    function ($scope, $http, $location, $timeout) {
    
    $scope.numberOfLines = 1;
    
    $scope.createNewTicket = function() {
        $http({
            method: 'POST',
            url: '/ticket',
            data: {
                number_of_lines: angular.fromJson($scope.numberOfLines)
            }
        }).then(function (response) {
            $scope.message = response.data.notice;
            // We clear this after 3 seconds
            $timeout(function () {
                $scope.message = null;
            }, 2000);
            console.log(response);
        }),function (error) {
            console.log(error);
        }
    }
    
    $scope.go = function($path) {
        $location.url($path);
    }
    
}]);

myApp.controller('addLinesController', ['$scope', '$rootScope', '$http', '$location', '$timeout', 
    function ($scope, $rootScope, $http, $location, $timeout) {
    
    $scope.ticketId = $rootScope.ticket.ID;
    console.log ($scope.ticketId);
    $scope.numberOfLines = 1;
    
    $scope.addLines = function() {
        $http({
            method: 'PUT',
            url: '/ticket/' + $scope.ticketId,
            data: {
                number_of_lines: angular.fromJson($scope.numberOfLines)
            }
        }).then(function (response) {
            $scope.message = response.data.notice;
            // We clear this after 3 seconds
            $timeout(function () {
                $scope.message = null;
            }, 2000);
            console.log(response);
        }),function (error) {
            console.log(error);
        }
    }
    
    $scope.go = function($path) {
        $location.url($path);
    }
    
}]);

myApp.controller('ticketController', ['$scope', '$rootScope', '$http', '$location', 
    function ($scope, $rootScope, $http, $location) {

    $scope.displayTickets = true;
    $scope.displayLines = false;
    
    $scope.go = function($path) {
        $scope.getTickets();
        $location.url($path);
    }

    $scope.addTicketLines = function($ticket) {
        $rootScope.ticket = $ticket;
        console.log ($rootScope.ticket);
        $location.url('/addlines');
    }

    $scope.swapViews = function() {
        $scope.getTickets();
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
        $scope.ticketLines = $ticket.lines;
        $scope.swapViews();
        console.log($ticket);
    }

    $scope.getResult = function($id) {
        console.log ($id);
        $http({
            method: 'GET',
            url: '/status/' + $id
        }).then(function (response) {
            $scope.ticketLines = response.data;
            console.log(response);
        }),function (error) {
            console.log(error);
        }
    }
    $scope.getTickets();
}]);
;(function ($, window, appLang) {
        'use strict';



    var app = new FaceS({
        language : appLang, // defined in index.html
        ngApp    : "app"
    });


    var userLanguage = FaceS.detectLanguage();

    // check if the user has already a session : do nothing
    // if the users comes for the fist time we start the session and we redirect it to the good language.
    if(!FaceS.hasSession()){
        FaceS.startSession();
        // if currently we are not in the good language, we redirect (only the first time)
        if(userLanguage !== app.language){
            window.location.replace(userLanguage + ".html");
        }
    }



    // configure our routes
    app.angular.config(function($routeProvider, $locationProvider) {
        $routeProvider.when('/', {
            templateUrl : app.getTemplate('home.html'),
            controller  : 'homeController'
        });

        $routeProvider.when('/serp-analysis', {
            templateUrl : app.getTemplate('serp-analysis.html'),
            controller  : 'mainController'
        });


    });

    app.angular.controller('mainController', function($scope, $http) {
        $scope.$watch("assignments", function (value) {
            prettyPrint();

        });
    });

    app.angular.controller('homeController', function($scope, $http) {
        $scope.$watch("assignments", function (value) {
            prettyPrint();

        });
    });






})(jQuery, this, appLang);
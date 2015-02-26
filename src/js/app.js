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

        $routeProvider.when('/compare', {
            templateUrl : app.getTemplate('compare.html'),
            controller  : 'mainController'
        });

        $routeProvider.when('/start', {
            templateUrl : app.getTemplate('start.html'),
            controller  : 'mainController'
        });

        $routeProvider.when('/support', {
            templateUrl : app.getTemplate('support.html'),
            controller  : 'mainController'
        });

    });

    app.angular.controller('mainController', function($scope, $http) {

    });

    app.angular.controller('homeController', function($scope, $http) {
        $scope.$watch("assignments", function (value) {//I change here
            prettyPrint();

            $("pre.prettyprint").css('opacity', 0).css("top",180)
                .slideDown('slow')
                .animate(
                { opacity: 1 , top:0 },
                { queue: false, duration: 'slow' }
            );

        });
    });






})(jQuery, this, appLang);
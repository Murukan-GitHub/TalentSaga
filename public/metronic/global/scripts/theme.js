/**
Core script to handle the entire theme and core functions
**/
var Theme = function() {

    var assetsPath = '/metronic/';

    // initializes main settings
    var handlePaths = function() {
        App.setAssetsPath(assetsPath);
    };

    //* END:CORE HANDLERS *//

    return {

        //main function to initiate the theme
        init: function() {
            handlePaths();
        }
    };
}();

jQuery(document).ready(function() {
   Theme.init(); // init metronic core componets
});

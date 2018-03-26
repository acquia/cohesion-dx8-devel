(function ($, Drupal, cssbeautify) {
    Drupal.behaviors.cohesionStylesheetInspector = {
        attach: function (context, settings) {
            $('pre code').each(function() {
                var $code = $(this);

                $code.html(cssbeautify($code.html().trim()))
                    .parent('pre').addClass('prettyprint');
            });
        }
    }
})(jQuery, Drupal, cssbeautify);
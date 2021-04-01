// you can override default options globally, so they apply to every .expander() call

// $.expander.defaults.slicePoint = 120;

var $j = jQuery.noConflict();
$j(document).ready(function() {
//$(document).ready(function() {
  // simple example, using all default options unless overridden globally
  // $j('div.expandable p').expander();

  // *** OR ***

  // override default options (also overrides global overrides)
  $j('div.expandable p').expander({
    slicePoint:       140,  // default is 100
    expandPrefix:     ' ', // default is '... '
    // expandText:       '[...]', // default is 'read more'
    expandText:       'more', // default is 'read more'
    // collapseTimer:    5000, // re-collapses after 5 seconds; default is 0, so no re-collapsing
    collapseTimer:    20000, // re-collapses after 5 seconds; default is 0, so no re-collapsing
    // userCollapseText: '[^]'  // default is 'read less'
    userCollapseText: 'less'  // default is 'read less'
  });

});

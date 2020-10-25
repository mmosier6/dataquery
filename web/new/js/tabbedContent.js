document.write('<style type="text/css">.tabber{display:none;}<\/style>');

var tabberOptions = { 

  'onClick': function(argsObj) {

    var t = argsObj.tabber; /* Tabber object */
    var i = argsObj.index; /* Which tab was clicked (0..n) */
    var div = this.tabs[i].div; /* The tab content div */

    /* Display a loading message */
    div.innerHTML = "<p>Loading...<\/p>";

    /* Fetch some html depending on which tab was clicked */
    var url = '/new/SPCProducts/validProducts.php?content=' +i;
    var pars = 'foo=bar&foo2=bar2'; /* just for example */
    var myAjax = new Ajax.Updater(div, url, {method:'get',parameters:pars});
  },

  'onLoad': function(argsObj) {
    /* Load the first tab */
    argsObj.index = 0;
    this.onClick(argsObj);
  }

}

var cart = new Array();
var rad = 0.01745;
var pi  = 3.1416;
var RRR = 6371;

function showLocation(e) {
	var dptr = document.getElementById("imgwrapper");
	var posX = e.offsetX?(e.offsetX):e.pageX-dptr.offsetLeft;
	var posY = e.offsetY?(e.offsetY):e.pageY-dptr.offsetTop;
        var lalo = pix_lalo(posX, posY);

	getClimoEnv(lalo[0], lalo[1]);

	var newX = dptr.offsetLeft+posX+20;
	var newY = dptr.offsetTop+posY-20;
	if (posX > 500) { var newX = dptr.offsetLeft+posX-420; }
	if (posY > 350) { var newY = dptr.offsetTop+posY-450; }

	// $("#climobox").css("left", newX);
	// $("#climobox").css("top",  newY);
	// $("#latlon").html("Tornado Climatology and Environments for: [" + lalo[0].toFixed(2) + ", " + lalo[1].toFixed(2) + "]");
	// $("#climobox").show();
        }

function initmap(clat, clon, slat1, slat2, slon, zoom, hgt, wid, meshsize) {
        var xyvals = new Array();

        cart['reflat1'] = slat1;
        cart['reflat2'] = slat2;
        cart['reflon'] = slon;
	
	var term1 = Math.log(Math.cos(d2r(cart['reflat1'])) / Math.cos(d2r(cart['reflat2'])));
	var term2 = Math.log(Math.tan(d2r(45.0 - (cart['reflat1']/2.0))) / Math.tan(d2r(45.0 - (cart['reflat2']/2.0))));
	if (term2 == 0) { cart['coneconst'] = 1;} else {cart['coneconst'] = term1/term2; }
	cart['clat'] = clat;
	cart['clon'] = clon;
	cart['gridsize'] = meshsize;
        cart['xshift']   = 0;
        cart['yshift']   = 0;
        cart['scrnwid']  = wid;
        cart['scrnlen']  = hgt;
	cart['xscle'] = zoom;
	cart['yscle'] = zoom;
        xyvals = lalo_xy(clat, clon);
        cart['xxl'] = xyvals[0];
        cart['yyl'] = xyvals[1];
        }

function lalo_xy(lat, lon) {
	var theta = d2r(lon - cart['reflon']) * cart['coneconst'];
	var term1 = RRR * Math.cos(d2r(cart['reflat1']));
	var term2 = Math.pow(Math.tan(d2r(45.0 - cart['reflat1']/2.0)), cart['coneconst']);
	var psi   = term1 / (cart['coneconst'] * term2);
	var rho1  = psi * term2;
	var rho   = psi * Math.pow((Math.tan(d2r(45.0 - lat / 2.0))), cart['coneconst']);
	var x = (1 / cart['gridsize']) * rho * Math.sin(theta);
	var y = (1 / cart['gridsize']) * (rho1 - rho * Math.cos(theta));
        return Array(x, y);
        }

function xy_lalo(x, y) {
	var term1 = RRR * Math.cos(d2r(cart['reflat1']));
	var rho1  = term1 / cart['coneconst'];
	var term2 = Math.pow(Math.tan(d2r(45.0 - cart['reflat1'] / 2.0)), cart['coneconst']);
	var psi   = term1 / (cart['coneconst'] * term2);
	var n1    = (x / (1.0 / cart['gridsize']));
	var d1    = (rho1 - (y / (1.0 / cart['gridsize'])));
	var theta = Math.atan(n1/d1);
	var lon   = cart['reflon'] + (1.0 / cart['coneconst']) * r2d(theta);
	
	if ((theta != 0) && (theta < pi)) {
		if (x > 0) { var nume = Math.pow(x,  (1.0 / cart['coneconst'])); }
		else {       var nume = Math.pow(-x, (1.0 / cart['coneconst'])); }

		if (theta > 0) { var denominator = Math.pow(1.0 / cart['gridsize'] * psi * Math.sin(theta),  (1.0 / cart['coneconst'])); }
		else {           var denominator = Math.pow(1.0 / cart['gridsize'] * psi * Math.sin(-theta), (1.0 / cart['coneconst'])); }
		var lat = 90.0 - 2.0 * r2d(Math.atan(nume/denominator));
		}
	else {
		var maxv = (y - (1.0 / cart['gridsize']) * rho1);
		if (maxv < 0) maxv = -maxv;
		var term3 = Math.pow(maxv, (1.0 / cart['coneconst']));
		var term4 = Math.pow(psi * (1.0 / cart['gridsize']), (1.0 / cart['coneconst']));
		var lat = 90.0 - 2.0 * r2d(Math.atan(term3/term4));
		}
	if ((lat == 90.0) || (lat == -90.0)) { lon = 0; };
	return Array(lat, lon);
	}

function pix_xy(xs, ys) {
        var x = ((((xs - cart['xshift']) - (cart['scrnwid'] / 2)) / cart['xscle']) + cart['xxl']);
        // var y = (cart['yyl'] - (((cart['scrnlen'] / 2) - (ys - cart['yshift'])) / cart['yscle']));
	var y = ((((-ys + cart['yshift']) + (cart['scrnlen'] / 2)) / cart['yscle']) + cart['yyl']);
        return Array(x, y);
        }

function pix_lalo(xs, ys) {
        xyvals = pix_xy(xs,ys);
        lalovals = xy_lalo(xyvals[0], xyvals[1]);
        return Array(lalovals[0], lalovals[1]);
        }

function d2r(val) {
	return val * rad;
	}

function r2d(val) {
	return val / rad;
	}

// #####################################################
// # MESOANALYSIS SPECIFIC CODE
// #####################################################

function setMap(sectornum) {
	// Settings for Daily climo maps.
	initmap(37.0, -96.30, 30, 50, -99, 7.6, 663, 935, 40);
	}

function getClimoEnv(lat, lon) {
	$.ajax({
		url: '/exper/mesoanalysis/new/getclimoenv.php?lat='+lat+'&lon='+lon+'&'+ new Date().getTime(),
		success: function(data) {
			var pdata = data.split("#$#$#");
			$("#climo1").html(pdata[1]);
			$("#climo2").html(pdata[2]);
			$("#envs").html(pdata[3]);
			}
		});
	}


var firstTime = 1;
var type="SPC";
var data;
var map;
var vectorSource;
function drawMapElements() { 
  jQuery("#showMapSwitcher").hide();
  if (firstTime < 1) {
    vectorSource.clear();
  }

    vectorSource = new ol.source.Vector({features: (new ol.format.GeoJSON()).readFeatures(mapJSON, {featureProjection: 'EPSG:3857', dataProjection:'EPSG:4326'})});
     
    var mapData = new ol.layer.Vector({
          source: vectorSource,
          style: function (feature) {
                  return getStyle(feature);
                        }
    });
    
    if (firstTime === 1) {    
      map = new ol.Map({
        target: 'map',
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM()
          }), mapData
        ],
        view: new ol.View({
          center: ol.proj.fromLonLat([-95, 38]),
          zoom: 4
        })
      }); //end var map
    } else {
      map.addLayer(mapData);
      }
    var highlightStyle = new ol.style.Style({
        stroke: new ol.style.Stroke({width:2,color:'#000000'})
    });
    var featureOverlay = new ol.layer.Vector({
        source: new ol.source.Vector(),
        map: map,
        style: function() {
        return highlightStyle;
        }
    });
 
    var highlight;
    var displayFeatureInfo = function(pixel) {

      var feature = map.forEachFeatureAtPixel(pixel, function(feature) {
        return feature;
      });

      var info = document.getElementById('info');
      if (feature) {
        if (type==='SPC' & feature.get('STATE') === "22") {
        info.innerHTML = '' + feature.get('NAME') + ' Parish<br>' + feature.get('ALLWATCH');
        } else if (type==='Tornado' & feature.get('STATE') === "22") {
        info.innerHTML = '' + feature.get('NAME') + ' Parish<br>' + feature.get('TOR');
        } else if (type==='Severe' & feature.get('STATE') === "22") {
        info.innerHTML = '' + feature.get('NAME') + ' Parish<br>' + feature.get('SVR');
        }
        else if (type==='SPC') {
        info.innerHTML = '' + feature.get('NAME') + ' County<br>' + feature.get('ALLWATCH');
        } else if (type==='Tornado') {
        info.innerHTML = '' + feature.get('NAME') + ' County<br>' + feature.get('TOR');
        } else if (type==='Severe') {
        info.innerHTML = '' + feature.get('NAME') + ' County<br>' + feature.get('SVR');
        }
      } else {
        info.innerHTML = 'Hover For<br>Watch Count';
      }
      if (feature !== highlight) {
        if (highlight) {
            featureOverlay.getSource().removeFeature(highlight);
        }
        if (feature) {
            featureOverlay.getSource().addFeature(feature);
        }
        highlight = feature;
      }
    };


map.on('pointermove', function(evt) {
        var pixel = map.getEventPixel(evt.originalEvent);
        displayFeatureInfo(pixel);
});

map.on('singleclick', function(evt) {
        popupOpen=1;
        var coordinate = evt.coordinate;
        map.getView().setCenter(coordinate);
        setTimeout(function() {
        map.getView().setZoom(8);
}, 100);
        });
makeLegend();

document.getElementById('export-png').addEventListener('click', function() {
  map.once('rendercomplete', function() {
    var mapCanvas = document.createElement('canvas');
    var size = map.getSize();
    mapCanvas.width = size[0];
    mapCanvas.height = size[1];
    var mapContext = mapCanvas.getContext('2d');
    Array.prototype.forEach.call(document.querySelectorAll('.ol-layer canvas'), function(canvas) {
      if (canvas.width > 0) {
        var opacity = canvas.parentNode.style.opacity;
        mapContext.globalAlpha = opacity === '' ? 1 : Number(opacity);
        var transform = canvas.style.transform;
        // Get the transform parameters from the style's transform matrix
        var matrix = transform.match(/^matrix\(([^\(]*)\)$/)[1].split(',').map(Number);
        // Apply the transform to the export map context
        CanvasRenderingContext2D.prototype.setTransform.apply(mapContext, matrix);
        mapContext.drawImage(canvas, 0, 0);
      }
    });
    if (navigator.msSaveBlob) {
      // link download attribuute does not work on MS browsers
      navigator.msSaveBlob(mapCanvas.msToBlob(), 'map.png');
    } else {
      var link = document.getElementById('image-download');
      link.href = mapCanvas.toDataURL();
      link.click();
    }
  });
  map.renderSync();
});
} //end function drawMapElements()

var getStyle = function (feature) {
if (type=== "Tornado") {
        x = feature.get('TOR')
        switch(true) {
        case (x<1):return new ol.style.Style({fill: new ol.style.Fill({color: [255, 255, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<2):return new ol.style.Style({fill: new ol.style.Fill({color: [255, 204, 204, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<3):return new ol.style.Style({fill: new ol.style.Fill({color: [255, 153, 153, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<5):return new ol.style.Style({fill: new ol.style.Fill({color: [255, 102, 102, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<7):return new ol.style.Style({fill: new ol.style.Fill({color: [255, 51, 51, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<10):return new ol.style.Style({fill: new ol.style.Fill({color: [255, 0, 0, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<13):return new ol.style.Style({fill: new ol.style.Fill({color: [204, 0, 0, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<17):return new ol.style.Style({fill: new ol.style.Fill({color: [153,  0,  0, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<21):return new ol.style.Style({fill: new ol.style.Fill({color: [102,  0,  0, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x>20):return new ol.style.Style({fill: new ol.style.Fill({color: [51,  0,  0, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        default:return new ol.style.Style({fill: new ol.style.Fill({color: [255, 0, 0, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
                }
        }
else if (type=== "SPC") {
        x = feature.get('ALLWATCH')
        switch(true) {
        case (x<1):return new ol.style.Style({fill: new ol.style.Fill({color: [255, 255, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5, color:'#808080'})});
        case (x<2):return new ol.style.Style({fill: new ol.style.Fill({color: [0, 255, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5, color:'#808080'})});
        case (x<4):return new ol.style.Style({fill: new ol.style.Fill({color: [0, 128, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5, color:'#808080'})});
        case (x<7):return new ol.style.Style({fill: new ol.style.Fill({color: [0, 0, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5, color:'#808080'})});
        case (x<10):return new ol.style.Style({fill: new ol.style.Fill({color: [128, 0, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5, color:'#808080'})});
        case (x<15):return new ol.style.Style({fill: new ol.style.Fill({color: [255, 0, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5, color:'#808080'})});
        case (x<20):return new ol.style.Style({fill: new ol.style.Fill({color: [255, 0, 128, 0.5]}), stroke: new ol.style.Stroke({width:.5, color:'#808080'})});
        case (x<25):return new ol.style.Style({fill: new ol.style.Fill({color: [255, 0, 0, 0.5]}), stroke: new ol.style.Stroke({width:.5, color:'#808080'})});
        case (x>24):return new ol.style.Style({fill: new ol.style.Fill({color: [255, 255, 0, 0.5]}), stroke: new ol.style.Stroke({width:.5, color:'#808080'})});
        default:return new ol.style.Style({fill: new ol.style.Fill({color: [255, 100, 100, 0.5]}), stroke: new ol.style.Stroke({width:.5, color:'#808080'})});
                }
        }
else if (type=== "Severe") {
        x = feature.get('SVR')
        switch(true) {
        case (x<1):return new ol.style.Style({fill: new ol.style.Fill({color: [255, 255, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<2):return new ol.style.Style({fill: new ol.style.Fill({color: [204, 204, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<3):return new ol.style.Style({fill: new ol.style.Fill({color: [153, 153, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<5):return new ol.style.Style({fill: new ol.style.Fill({color: [102, 102, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<7):return new ol.style.Style({fill: new ol.style.Fill({color: [51, 51, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<10):return new ol.style.Style({fill: new ol.style.Fill({color: [0, 0, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<13):return new ol.style.Style({fill: new ol.style.Fill({color: [0, 0, 204, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<17):return new ol.style.Style({fill: new ol.style.Fill({color: [0, 0, 153, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x<21):return new ol.style.Style({fill: new ol.style.Fill({color: [0,  0, 102, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        case (x>20):return new ol.style.Style({fill: new ol.style.Fill({color: [0,  0, 51, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
        default:return new ol.style.Style({fill: new ol.style.Fill({color: [0, 0, 255, 0.5]}), stroke: new ol.style.Stroke({width:.5,color:'#808080'})});
                }
        }
};
                       
function selectSevere() {
type= "Severe";
jQuery("#all").prop( "checked", false);
jQuery("#tornado").prop( "checked", false);
jQuery("#severe").prop( "checked", true);
drawMapElements();
firstTime= 0;
jQuery("#mapTitle").html("" + startDate.slice(4,6) +"/"+ startDate.slice(6,8) +"/"+ startDate.slice(0,4) +" to "+endDate.slice(4,6) +"/"+ endDate.slice(6,8) +"/"+ endDate.slice(0,4) +" Severe Watches");
} //end selectSevere
function selectAll() {
type= "SPC";
jQuery("#severe").prop( "checked", false);
jQuery("#tornado").prop( "checked", false);
jQuery("#all").prop( "checked", true);
drawMapElements();
firstTime= 0;
jQuery("#mapTitle").html("" + startDate.slice(4,6) +"/"+ startDate.slice(6,8) +"/"+ startDate.slice(0,4) +" to "+endDate.slice(4,6) +"/"+ endDate.slice(6,8) +"/"+ endDate.slice(0,4) +" SPC Watches");
} //end selectAll
function selectTornado() {
type= "Tornado";
jQuery("#all").prop( "checked", false);
jQuery("#severe").prop( "checked", false);
jQuery("#tornado").prop( "checked", true);
drawMapElements();
firstTime= 0;
jQuery("#mapTitle").html("" + startDate.slice(4,6) +"/"+ startDate.slice(6,8) +"/"+ startDate.slice(0,4) +" to "+endDate.slice(4,6) +"/"+ endDate.slice(6,8) +"/"+ endDate.slice(0,4) +" Tornado Watches");
} //end selectTornado

function selectYear() {
year = jQuery("#RangeSlider").val();
drawMapElements();
firstTime= 0;
jQuery("#mapTitle").html("" + year + " " + type + " Watches");
jQuery("#year").html("" + year + "");
} //end selectYear

jQuery("#year").html("Use slider to see past years");

function showMapSwitcher() {
jQuery("#mapSwitcher").show();
jQuery("#showMapSwitcher").hide();
}

function hideMapSwitcher() {
jQuery("#mapSwitcher").hide();
jQuery("#showMapSwitcher").show();
}
function getColor(d) {
if (type==="Tornado") {
return d > 20 ? "#330000" :
       d > 16 ? "#660000" :
       d > 12 ? "#990000" :
       d >  9 ? "#cc0000" :
       d >  6 ? "#ff0000" :
       d >  4 ? "#ff3333" :
       d >  2 ? "#ff6666" :
       d >  1 ? "#ff9999" :
       d >  0 ? "#ffcccc" :
                "#ffffff" ;
}
else if (type==="Severe") {
return d > 20 ? "#000033" :
       d > 16 ? "#000066" :
       d > 12 ? "#000099" :
       d >  9 ? "#0000cc" :
       d >  6 ? "#0000ff" :
       d >  4 ? "#3333ff" :
       d >  2 ? "#6666ff" :
       d >  1 ? "#9999ff" :
       d >  0 ? "#ccccff" :
                "#ffffff" ;
}
else if (type==="SPC") {
return d > 25 ? "#ffff00" :
       d > 20 ? "#ff00ff" :
       d > 15 ? "#bf00ff" :
       d > 10 ? "#8000ff" :
       d >  7 ? "#0000ff" :
       d >  4 ? "#0000ff" :
       d >  2 ? "#0080ff" :
       d >  1 ? "#0080ff" :
       d >  0 ? "#00ffff" :
                "#ffffff" ;
}
}

function makeLegend() {
if (type==="SPC") {
document.getElementById('mapLegend').style.bottom = 30 + "px";
document.getElementById('mapLegend').style.right = 2 + "px";
document.getElementById('mapLegend').style.height = 185 + "px";
document.getElementById('mapLegend').style.width = 95 + "px";

var div = document.getElementById('mapLegend'),
count = [0,1,2,4,7,10,15,20,25],
labels = [];

div.innerHTML= '<b>Total Watches</b><br>'

for (var i= 0; i < count.length; i++) {
div.innerHTML +=
'<i style="background:' + getColor(count[i] + 1) + '"></i> ' +
        count[i] + (count[i + 1] ? ' to ' + count[i + 1] + '<br>' : '+');
}

return div;
}
else {
document.getElementById('mapLegend').style.bottom = 30 + "px";
document.getElementById('mapLegend').style.right = 2 + "px";
document.getElementById('mapLegend').style.height = 185 + "px";
document.getElementById('mapLegend').style.width = 115 + "px";
var div = document.getElementById('mapLegend'),
count = [0,1,2,4,6,9,12,16,20],
labels = [];

div.innerHTML= '<b>' + type + ' Watches</b><br>'

for (var i= 0; i < count.length; i++) {
div.innerHTML +=
'<i style="background:' + getColor(count[i] + 1) + '"></i> ' +
        count[i] + (count[i + 1] ? ' to ' + count[i + 1] + '<br>' : '+');
}

return div;
}
};

function redrawMap() {
setTimeout(function() {
	map.updateSize();
	}, 200);
}


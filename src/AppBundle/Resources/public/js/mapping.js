/**
 * mapping.js - Main Map Script
 */

defaultWeight = 1;
boldWeight = 3;
outlineColor = "black";
outlineHighlightColor = "black";

var allSchoolData;
var selectedCatchmentMarkers = [];
var allFeatureLayers = [];
var allFeatures = [];
var allMarkers = [];
var placedMarkers = [];
var visibleMarkers = [];
var gjLayer;

var southWest = L.latLng(39.865215995391, -75.2838134765625),
    northEast = L.latLng(40.144528401949176, -74.81620788574219);
var mapBounds = L.latLngBounds(southWest, northEast);

var map = L.map('map', {
    zoomControl: false,
    minZoom: 12,
    maxBounds: mapBounds
}).setView([40.00, -75.05], 12);

var accessToken = 'pk.eyJ1IjoibWFyaWFrZWwiLCJhIjoiODYxMjQzZWExZjg5ZjI0NmNhZTc1NTViOGRlNmE2NWQifQ.4ayKf1XQiGA0IC3UG3zMkg';
L.tileLayer('https://api.mapbox.com/v4/mapbox.light/{z}/{x}/{y}.png?access_token=' + accessToken, {
    minZoom: 12,
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
    id: 'examples.map-20v6611k',
    bounds: mapBounds
}).addTo(map);

var myStyle = {
    "color": "#d30000",
    "fillColor": "#d30000",
    "weight": 2,
    "opacity": 0.9
};

function getColor(count) {
    return  count > 10  ?  '#08519c' :
            count > 6   ?  '#3182bd' :
            count > 3   ?  '#6baed6' :
            count > 0   ?  '#bdd7e7' :
                           '#ccc';
}

function getActivityCount(feature) {
    var total = 0;
    for (code in feature.markers) {
        total += (typeof allSchoolData[code].total == "undefined") ? 0 : allSchoolData[code].total;
    }

    feature.activityCount = total;

    return total;
}

function style(feature) {
    return {
        color: outlineColor,
        fillColor: getColor(getActivityCount(feature)),
        weight: defaultWeight,
        opacity: 0.9,
        fillOpacity: 0.9
    };
};

function totalActivityCountByFeature(feature) {
    var total = getActivityCount(feature);

    if (total == 1) {
        return "There is currently <strong>"+total+"</strong> active project in this catchment.";
    } else {
        return "There are currently <strong>"+total+"</strong> active projects in this catchment.";
    }
}

function highlightFeature(e) {
    var layer = e.target;

    layer.setStyle({
        weight: 5,
        color: '#666',
        fillOpacity: 0.9
    });

    highlightedLayer = layer;

    // console.log(layer.feature.name);
    if (!L.Browser.ie && !L.Browser.opera) {
        layer.bringToFront();
        if (typeof selectedLayer != "undefined") {
            selectedLayer.bringToFront(); // This one should always be foremost
        }
    }

    info.update(layer.feature.name, layer.feature.properties, layer.feature.activityCount);
}

function resetHighlight(e) {
    var layer = e.target;

    // only reset if the layer we're resetting is not selected
    var selectedLayerName = typeof selectedLayer == "undefined" ? "none selected" : selectedLayer.feature.name;
    if ((typeof (selectedLayer) == "undefined" || layer != selectedLayer)) {
        gjLayer.resetStyle(layer);
        info.update();
    }
}

function zoomToFeature(e) {
    if (typeof selectedLayer != "undefined" && selectedLayer != e.target) {
        gjLayer.resetStyle(selectedLayer);
        removeVisibleMarkers();
    }
    // console.log('layer clicked');
    // map.fitBounds(e.target.getBounds(), {maxZoom: 14});
    // map.panBy([150, 0]);
    showMarkersInFeature(e.target.feature);
    selectedLayer = e.target;

    if (!L.Browser.ie && !L.Browser.opera) {
        selectedLayer.bringToFront();
    }

    getActivityHtmlOutput(selectedLayer.feature);
}

function determineFeatureMarkers() {
    for (i in allFeatures) {
        feature = allFeatures[i];
        feature.markers = getCatchmentMarkers(feature);
        gjLayer.resetStyle(allFeatureLayers[i]);
    }
}

function getCatchmentMarkers(feature) {
    var markers = {};
    markers[feature.properties.ES_ID] = allMarkers[feature.properties.ES_ID];
    markers[feature.properties.MS_ID] = allMarkers[feature.properties.MS_ID];
    markers[feature.properties.HS_ID] = allMarkers[feature.properties.HS_ID];

    placedMarkers[feature.properties.ES_ID] = 1;
    placedMarkers[feature.properties.MS_ID] = 1;
    placedMarkers[feature.properties.HS_ID] = 1;

    return markers;
}

function getActivityHtmlOutput(feature) {
    // School Names and Codes for District
    var schoolCodes = [feature.properties.ES_ID];
    var schoolNames = [feature.properties.ES_Name];
    if (feature.properties.ES_ID != feature.properties.MS_ID) {
        schoolCodes.push(feature.properties.MS_ID);
        schoolNames.push(feature.properties.MS_Name);
    }
    if (feature.properties.ES_ID != feature.properties.HS_ID && feature.properties.MS_ID != feature.properties.HS_ID) {
        schoolCodes.push(feature.properties.HS_ID);
        schoolNames.push(feature.properties.HS_Name);
    }

    // Populate with data about this district
    $('#activities-list h4').eq(0).html(feature.name);
    $('#activities-list .activity-info').eq(0).html(totalActivityCountByFeature(feature));

    var schoolActivityCounts;
    $('#activities-list #activities-details').html('');
    for (code in feature.markers) {
        if (typeof activityCounts[code] != "undefined") {
            var categories = activityCounts[code]['categories'];
            if (typeof categories != "undefined") {
                schoolActivityCounts = activityCounts[code];
                $('#activities-list #activities-details').append("<div class='school-overview' id='school-"+code+"'><h5>"+ activityCounts[code].name +" ("+schoolActivityCounts.total+")</h5></div>")
                var activities = $("<div class='activity-listing'></div>");
                var key;
                var output = "";
                for (var j = 0; j < Object.keys(categories).length; j++) {
                    key = Object.keys(activityCounts[code]['categories'])[j];
                    output += "<li>"+key+" <span class='badge'>"+activityCounts[code]['categories'][key]+"</span></li>";
                }
                activities.html(output);
                $("#school-"+code).eq(0).append(activities);
            }
        }
    }

    $('#activities-list').show();
}

function onEachFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        click: zoomToFeature
    });

    feature.name = feature.properties.ES_Short+" Catchment Area";
    allFeatures.push(feature);
    allFeatureLayers.push(layer);
}

function showMarkersInFeature(feature) {
    var mark;
    if (typeof feature.markers != "undefined") {
        for (code in feature.markers) {
            mark = feature.markers[code];
            map.addLayer(mark);
            visibleMarkers.push(mark);
        }
    }
}

function removeVisibleMarkers() {
    for (var i = 0; i < visibleMarkers.length; i++) {
        map.removeLayer(visibleMarkers[i]);
    }

    visibleMarkers = [];
}

// Use Activity Counts To Build Map.

// Markers for all points.
var activityCounts, allSchoolData;
//var url = Routing.generate('school_activity_data');
var url = "/school-mapping/web/schoolActivities_static.json";
$.getJSON(url, function(data){
    allSchoolData = data;
    activityCounts = allSchoolData;

    // Add Markers for All Schools -- May be temp...
    var schoolCodes = Object.keys(allSchoolData);
    var code, iconColor;
    for (var i = 0; i < schoolCodes.length; i++) {
        code = schoolCodes[i];
        lat = allSchoolData[code].latitude;
        lng = allSchoolData[code].longitude;

        if (typeof activityCounts[code].total == "undefined") {
            iconColor = "#ccc";
        } else if (activityCounts[code].total < 3 && activityCounts[code].type == "District") {
            iconColor = "#d30000";
        } else if (activityCounts[code].type == "District") {
            iconColor = "#891e1e";
        } else {
            iconColor = "#00112d";
        }
        icon = L.MakiMarkers.icon({icon: null, color: iconColor, size: "m"});

        mark = L.marker([lat, lng], {icon:icon});
        allMarkers[code] = mark;
        var count = typeof allSchoolData[code].total == "undefined" ? 0 : allSchoolData[code].total;
        mark.bindPopup("<b>"+allSchoolData[code].name+" (" + count + ")</b><br>"+allSchoolData[code].type+" - "+allSchoolData[code].gradeLevel);
    }

    /**
     * NOTE: GeoJSON file successfully converted using:
     * ogr2ogr -f GeoJSON -t_srs crs:84 SDP_Boundaries_1415.geojson SDP_Boundaries_1415.shp
     */
    gjLayer = L.geoJson(sdpBoundaries, {
        style: style,
        onEachFeature: onEachFeature
    });

    determineFeatureMarkers();

    // Remaining Schools Also Need Regions
    placeRemainingMarkers();

    gjLayer.addTo(map);

});

function placeRemainingMarkers() {
    for (code in allMarkers) {
        if (!(code in placedMarkers)) {
            mark = allMarkers[code];
            markerLayer = leafletPip.pointInLayer(mark.getLatLng(), gjLayer, true);
            if (markerLayer.length) {
                markerLayer[0].feature.markers[code] = mark;
                placedMarkers[code] = 1;
                gjLayer.resetStyle(markerLayer[0]);
            }
        }
    }
}

function placeMarker(code) {
    var layer;
    for (var i = 0; i < allFeatureLayers.length; i++) {
        layer = allFeatureLayers[i];
        if (layer.getBounds().contains(allMarkers[code].getLatLng())) {
            console.log(allMarkers[code].getLatLng(), "Placing marker for: "+allSchoolData[code].name+" in '"+layer.feature.name+"'");
            layer.feature.markers[code] = allMarkers[code];
            placedMarkers[code] = 1;
            gjLayer.resetStyle(layer);

            return;
        }
    }
    console.log("Could Not Place Marker for: ",allSchoolData[code].name);
}

/** ---------- CATCHMENT REGION INFO ---------- **/
var info = L.control({position: 'topleft'});

info.onAdd = function (map) {
    this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
    this.update();
    return this._div;
};

// Zoom Controls
var zoomHome = L.Control.zoomHome();
zoomHome.addTo(map);

// method that we will use to update the control based on feature properties passed
info.update = function (catchmentName, props, activityCount) {
    if (!catchmentName && !props && typeof selectedLayer != "undefined") {
        // Use info from selected layer.
        catchmentName = selectedLayer.feature.name;
        props = selectedLayer.feature.properties;
        activityCount = selectedLayer.feature.activityCount;
    }
    if (catchmentName && props) {
        var output = '<h4>'+ catchmentName + ' <span class="badge">'+ activityCount +'</span></h4>';
        output += "<div><strong>" + allSchoolData[props.ES_ID].gradeLevel + "</strong>: " + allSchoolData[props.ES_ID].name + "<br></div>";
        if (props.ES_ID != props.MS_ID) {
            output += "<div><strong>" + allSchoolData[props.MS_ID].gradeLevel + "</strong>: " + allSchoolData[props.MS_ID].name + "<br></div>";
        }
        if (props.HS_ID != props.MS_ID) {
            output += "<div><strong>" + allSchoolData[props.HS_ID].gradeLevel + "</strong>: " + allSchoolData[props.HS_ID].name + "<br></div>";
        }
    } else {
        var output = 'Hover over a catchment region.';
    }

    // Whether to highlight the box or not.
    if (typeof selectedLayer != "undefined" && selectedLayer.feature.name == catchmentName) {
        $('.info').addClass('active');
    } else {
        $('.info').removeClass('active');
    }

    this._div.innerHTML = output;
};

info.addTo(map);
/** --------------------------------------------- **/

map.on('click', function(e){
    if (typeof selectedLayer != "undefined") {
        // Reset weight to default
        gjLayer.resetStyle(selectedLayer);

        for (var i = 0; i < selectedCatchmentMarkers.length; i++) {
            var mark = selectedCatchmentMarkers[i];
            map.removeLayer(mark);
        }
        // Clear out data.
        selectedCatchmentMarkers = [];
    }
});

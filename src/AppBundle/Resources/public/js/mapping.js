/** 
 * mapping.js - Main Map Script
 */

defaultWeight = 1;
boldWeight = 3;
outlineColor = "#777";

// var map = L.map('map').setView([51.505, -0.09], 13);
var map = L.map('map').setView([40.00, -75.166], 12);

L.tileLayer('https://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
    id: 'examples.map-20v6611k'
}).addTo(map);

var myStyle = {
    "color": "#d30000",
    "fillColor": "#d30000",
    "weight": 2,
    "opacity": 0.9
};

styleFunction = function(feature) {
    // console.log(feature.properties.ES_ID, feature.properties.MS_ID, feature.properties.HS_ID);
    if (typeof activityCounts[feature.properties.ES_ID] != "undefined" || typeof activityCounts[feature.properties.MS_ID] != "undefined" || typeof activityCounts[feature.properties.HS_ID] != "undefined") {
        var total = (typeof activityCounts[feature.properties.ES_ID] == "undefined") ? 0 : activityCounts[feature.properties.ES_ID]['total'];
        total += (typeof activityCounts[feature.properties.MS_ID] == "undefined") ? 0 : activityCounts[feature.properties.MS_ID]['total'];
        total += (typeof activityCounts[feature.properties.HS_ID] == "undefined") ? 0 : activityCounts[feature.properties.HS_ID]['total'];
        console.log(feature.properties.ES_Short+" Catchment Area: ", total)
        return {
            "color": outlineColor,
            "fillColor": "#d30000",
            "weight": defaultWeight,
            "opacity": 0.9,
            "fillOpacity": 0.6
        };
    }

    if ((feature.properties.HS_Grade == "6-12" || feature.properties.HS_Grade == "7-12") || feature.properties.ES_Name == "Penn Alexander" || feature.properties.ES_Name == "Lea, Henry C") {
        return {
            "color": outlineColor,
            "fillColor": "#d30000",
            "weight": defaultWeight,
            "opacity": 0.9,
            "fillOpacity": 0.6
        };
    } else if (feature.properties.ES_Grade == "K-8") {
        return {
            "color": outlineColor,
            "fillColor": "#ffa500",
            "weight": defaultWeight,
            "opacity": 0.9,
            "fillOpacity": 0.6
        };
    }

    return {
        "color": outlineColor,
        "fillColor": "#d30000",
        "weight": defaultWeight,
        "opacity": 0.9,
        "fillOpacity": 0.4
    };
};

var url = Routing.generate('get_activity_counts');
var activityCounts;
$.getJSON(url, function(data){
    activityCounts = data;

    /**
     * NOTE: GeoJSON file successfully converted using:
     * ogr2ogr -f GeoJSON -t_srs crs:84 SDP_Boundaries_1415.geojson SDP_Boundaries_1415.shp
     */
    var gjLayer = L.geoJson(sdpBoundaries, {
        style: styleFunction,
        onEachFeature: function (feature, layer) {
            var schoolInfo = "<strong>"+feature.properties.ES_Short+" Catchment Area</strong><br>";
            schoolInfo += "Elementary School: "+feature.properties.ES_Name;
            schoolInfo += "<br>Middle School: "+feature.properties.MS_Name;
            schoolInfo += "<br>High School: "+feature.properties.HS_Name;
            layer.bindPopup(schoolInfo);

            layer.on('click', function(){
                if (typeof selectedLayer != "undefined") {
                    selectedLayer.setStyle({weight: defaultWeight});
                }

                selectedLayer = this;
                this.setStyle({weight: boldWeight});
            });
        }
    });

    gjLayer.addTo(map);
});



map.on('click', function(e){
    if (typeof selectedLayer != "undefined") {
        selectedLayer.setStyle({weight: defaultWeight});
    }
});
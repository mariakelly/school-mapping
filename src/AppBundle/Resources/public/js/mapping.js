/**
 * mapping.js - Main Map Script
 */

defaultWeight = 1;
boldWeight = 3;
outlineColor = "black";
outlineHighlightColor = "black";

var allSchoolData;
var url = Routing.generate('school_list', {'includeLocation': 1});
var selectedCatchmentMarkers = [];


// var map = L.map('map').setView([51.505, -0.09], 13);
var map = L.map('map').setView([39.99, -75.066], 12);

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
var colors = ['#74a9cf','#2b8cbe','#045a8d'];

styleFunction = function(feature) {
    // console.log(feature.properties.ES_ID, feature.properties.MS_ID, feature.properties.HS_ID);
    if (typeof activityCounts[feature.properties.ES_ID] != "undefined" || typeof activityCounts[feature.properties.MS_ID] != "undefined" || typeof activityCounts[feature.properties.HS_ID] != "undefined") {
        var total = (typeof activityCounts[feature.properties.ES_ID] == "undefined") ? 0 : activityCounts[feature.properties.ES_ID]['total'];
        total += (typeof activityCounts[feature.properties.MS_ID] == "undefined") ? 0 : activityCounts[feature.properties.MS_ID]['total'];
        total += (typeof activityCounts[feature.properties.HS_ID] == "undefined") ? 0 : activityCounts[feature.properties.HS_ID]['total'];
        console.log(feature.properties.ES_Short+" Catchment Area: ", total)

        var color;
        if (total < 5) {
            color = colors[0];
        } else if (total < 10) {
            color = colors[1];
        } else {
            color = colors[2];
        }

        return {
            "color": outlineColor,
            "fillColor": color,
            "weight": defaultWeight,
            "opacity": 1,
            "fillOpacity": 0.9
        };
    }

    return {
        "color": outlineColor,
        "fillColor": "#bdc9e1",
        "weight": defaultWeight,
        "opacity": 0.9,
        "fillOpacity": 0.9
    };
};

activityData = function(feature) {
    var total = 0;
    if (typeof activityCounts[feature.properties.ES_ID] != "undefined" || typeof activityCounts[feature.properties.MS_ID] != "undefined" || typeof activityCounts[feature.properties.HS_ID] != "undefined") {
        total = (typeof activityCounts[feature.properties.ES_ID] == "undefined") ? 0 : activityCounts[feature.properties.ES_ID]['total'];
        total += (typeof activityCounts[feature.properties.MS_ID] == "undefined" || feature.properties.MS_ID == feature.properties.ES_ID) ? 0 : activityCounts[feature.properties.MS_ID]['total'];
        total += (typeof activityCounts[feature.properties.HS_ID] == "undefined" || feature.properties.MS_ID == feature.properties.HS_ID) ? 0 : activityCounts[feature.properties.HS_ID]['total'];
    }

    if (total == 1) {
        return "There is currently <strong>"+total+"</strong> active project in this catchment.";
    } else {
        return "There are currently <strong>"+total+"</strong> active projects in this catchment.";
    }
}


// Use Activity Counts To Build Map.
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
            // layer.bindPopup(schoolInfo);

            var self = feature;
            layer.on('click', function(){
                if (typeof selectedLayer != "undefined") {
                    // Set existing one back to default style.
                    selectedLayer.setStyle({
                        color: outlineColor,
                        weight: defaultWeight
                    });
                }

                selectedLayer = this;
                this.setStyle({
                    color: outlineHighlightColor,
                    weight: boldWeight
                });
                console.log(this.feature.properties);
                map.fitBounds(this.getBounds(), {maxZoom: 13});
                map.panBy([150, 0]);


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
                $('#activities-list h4').eq(0).html(this.feature.properties.ES_Short+" Catchment Area");
                $('#activities-list .activity-info').eq(0).html(activityData(this.feature));

                var schoolActivityCounts;
                $('#activities-list #activities-details').html('');
                for (var i = 0; i < schoolCodes.length; i++) {
                    if (typeof activityCounts[schoolCodes[i]] != "undefined") {
                        schoolActivityCounts = activityCounts[schoolCodes[i]];
                        $('#activities-list #activities-details').append("<div class='school-overview' id='school-"+schoolCodes[i]+"'><h5>"+ activityCounts[schoolCodes[i]].name +" ("+schoolActivityCounts.total+")</h5></div>")
                        var activities = $("<div class='activity-listing'></div>");
                        var key;
                        var output = "";
                        for (var j = 0; j < Object.keys(activityCounts[schoolCodes[i]]['categories']).length; j++) {
                            key = Object.keys(activityCounts[schoolCodes[i]]['categories'])[j];
                            output += "<li>"+key+" <span class='badge'>"+activityCounts[schoolCodes[i]]['categories'][key]+"</span></li>";
                        }
                        activities.html(output);
                        $("#school-"+schoolCodes[i]).eq(0).append(activities);
                    }
                }

                $('#activities-list').show();

                // Add Markers to Map
                var icon;
                var mark;
                // Clear out data.
                for (var i = 0; i < selectedCatchmentMarkers.length; i++) {
                    var mark = selectedCatchmentMarkers[i];
                    map.removeLayer(mark);
                }
                selectedCatchmentMarkers = [];
                for (var i = 0; i < schoolCodes.length; i++) {
                    schoolID = schoolCodes[i];

                    // if (typeof activityCounts[schoolID] == "undefined") {
                    //     icon = L.MakiMarkers.icon({icon: null, color: "#ccc", size: "m"});
                    // } else {
                    //     icon = L.MakiMarkers.icon({icon: null, color: "#d30000", size: "m"});
                    // }


                    // if (typeof allSchoolData[schoolID] != "undefined") {
                    //     var lat = allSchoolData[schoolID].latitude;
                    //     var lng = allSchoolData[schoolID].longitude;
                    //     var mark = L.marker([lat, lng], {icon:icon}).addTo(map);
                    //     mark.bindPopup("<b>"+schoolNames[i]+"</b>");
                    //     selectedCatchmentMarkers.push(mark);
                    // }
                }
            });
        }
    });

    gjLayer.addTo(map);

    // Markers for all points.
    var url = Routing.generate('school_list', {'includeLocation': 1});
    $.getJSON(url, function(data){
        allSchoolData = data;

        // Add Markers for All Schools -- May be temp...
        var schoolCodes = Object.keys(allSchoolData);
        var code;
        for (var i = 0; i < schoolCodes.length; i++) {
            code = schoolCodes[i];
            lat = allSchoolData[code].latitude;
            lng = allSchoolData[code].longitude;

            if (typeof activityCounts[code] == "undefined") {
                icon = L.MakiMarkers.icon({icon: null, color: "#ccc", size: "m"});
            } else {
                icon = L.MakiMarkers.icon({icon: null, color: "#d30000", size: "m"});
            }
            mark = L.marker([lat, lng], {icon:icon}).addTo(map);        
            mark.bindPopup("<b>"+allSchoolData[code].name+"</b>");
        }
    });


});

map.on('click', function(e){
    if (typeof selectedLayer != "undefined") {
        // Reset weight to default
        selectedLayer.setStyle({
            color: outlineColor,
            weight: defaultWeight
        });
        for (var i = 0; i < selectedCatchmentMarkers.length; i++) {
            var mark = selectedCatchmentMarkers[i];
            map.removeLayer(mark);
        }
        // Clear out data.
        selectedCatchmentMarkers = [];
    }
});
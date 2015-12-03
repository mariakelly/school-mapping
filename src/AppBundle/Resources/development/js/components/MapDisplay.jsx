/**
 * MapDisplay Component
 * -   Displays the main map using Leaflet.js
 */

import React from 'react';

var $ = require('jquery');
var leafletPip = require('leaflet-pip');

var MapDisplay = React.createClass({
    componentDidMount: function() {
        this._construct();
        this.infoAndZoomControls();
        this.buildMap();
    },
    _construct: function() {
        /* -------------------------
         * |    GLOBAL VARIABLES   |
         * ------------------------- */
        this.defaultWeight = 1;
        this.boldWeight = 3;
        this.outlineColor = "black";
        this.outlineHighlightColor = "black";

        this.activityCounts = null;
        this.allSchoolData;
        this.selectedCatchmentMarkers = [];
        this.allFeatureLayers = [];
        this.allFeatures = [];
        this.allMarkers = [];
        this.info = null;
        this.infoDisplayed = true;
        this.placedMarkers = [];
        this.visibleMarkers = [];
        this.gjLayer;
        this.accessToken = 'pk.eyJ1IjoibWFyaWFrZWwiLCJhIjoiODYxMjQzZWExZjg5ZjI0NmNhZTc1NTViOGRlNmE2NWQifQ.4ayKf1XQiGA0IC3UG3zMkg';

        this.southWest = L.latLng(39.84624001115309, -75.51624298095703);
        this.northEast = L.latLng(40.16050911251291, -74.88796234130858);
        this.mapBounds = L.latLngBounds(this.southWest, this.northEast);

        this.mapCenterWhenCatchmentOpened = L.latLng(39.9638328330579, -75.27196884155273);

        // var center = [39.9858014704838, -75.13790130615234];
        // var center = [39.95606977009003, -75.14991760253906];
        var center = [40.00, -75.14991760253906];
        this.map = L.map('map', {
            zoomControl: false,
            minZoom: 12,
            maxBounds: this.mapBounds
        }).setView(center, 12);

        L.tileLayer('https://api.mapbox.com/v4/mapbox.light/{z}/{x}/{y}.png?access_token=' + this.accessToken, {
            minZoom: 12,
            maxZoom: 14,
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="http://mapbox.com">Mapbox</a>',
            id: 'examples.map-20v6611k',
            bounds: this.mapBounds
        }).addTo(this.map);

        this.myStyle = {
            "color": "#d30000",
            "fillColor": "#d30000",
            "weight": 2,
            "opacity": 0.9
        };
    },
    getColor: function(count) {
        // return  count > 10  ?  '#08519c' :
        //         count > 6   ?  '#3182bd' :
        //         count > 3   ?  '#6baed6' :
        //         count > 0   ?  '#bdd7e7' :
        //                        '#ccc';
        return  count > 10  ?  this.props.colors[3] :
                count > 6   ?  this.props.colors[2] :
                count > 3   ?  this.props.colors[1] :
                count > 0   ?  this.props.colors[0] :
                               '#ccc';
    },
    getActivityCount: function(feature) {
        var total = 0;
        for (var code in feature.markers) {
            total += (typeof this.allSchoolData[code].total == "undefined") ? 0 : this.allSchoolData[code].total;
        }

        feature.activityCount = total;

        return total;
    },
    style: function(feature) {
        return {
            color: this.outlineColor,
            fillColor: this.getColor(this.getActivityCount(feature)),
            weight: this.defaultWeight,
            opacity: 0.9,
            fillOpacity: 0.9
        };
    },
    totalActivityCountByFeature: function(feature) {
        var total = this.getActivityCount(feature);

        if (total == 1) {
            return "There is currently <strong>"+total+"</strong> active project in this catchment.";
        } else {
            return "There are currently <strong>"+total+"</strong> active projects in this catchment.";
        }
    },
    highlightFeature: function(e) {
        var layer = e.target;

        layer.setStyle({
            weight: 5,
            color: '#666',
            fillOpacity: 0.9
        });

        var highlightedLayer = layer;

        // console.log(layer.feature.name);
        if (!L.Browser.ie && !L.Browser.opera) {
            layer.bringToFront();
            if (typeof this.selectedLayer != "undefined") {
                this.selectedLayer.bringToFront(); // This one should always be foremost
            }
        }

        this.info.update(layer.feature.name, layer.feature.properties, layer.feature.activityCount);
    },
    resetHighlight: function(e) {
        var layer = e.target;

        // only reset if the layer we're resetting is not selected
        var selectedLayerName = typeof this.selectedLayer == "undefined" ? "none selected" : this.selectedLayer.feature.name;
        if ((typeof (this.selectedLayer) == "undefined" || layer != this.selectedLayer)) {
            this.gjLayer.resetStyle(layer);
            this.info.update();
        }
    },
    clearSelected: function() {
        if (typeof this.selectedLayer != "undefined") {
            this.gjLayer.resetStyle(this.selectedLayer);
            this.removeVisibleMarkers();
            this.selectedLayer = undefined;
            this.info.update();
        }
    },
    zoomToFeature: function(e) {
        if (typeof this.selectedLayer != "undefined" && this.selectedLayer != e.target) {
            this.gjLayer.resetStyle(this.selectedLayer);
            this.removeVisibleMarkers();
        }
        // console.log('layer clicked');
        // map.fitBounds(e.target.getBounds(), {maxZoom: 14});
        // map.panBy([150, 0]);
        this.showMarkersInFeature(e.target.feature);
        this.selectedLayer = e.target;
        console.log(e.target.getBounds().getCenter());

        // this.map.panTo(this.mapCenterWhenCatchmentOpened);
        var catchmentCenter = e.target.getBounds().getCenter();
        var adjustedLng = catchmentCenter.lng - 0.05;
        var centerPosition = L.latLng(catchmentCenter.lat, adjustedLng);
        this.map.panTo(centerPosition);

        if (!L.Browser.ie && !L.Browser.opera) {
            this.selectedLayer.bringToFront();
        }

        // this.getActivityHtmlOutput(this.selectedLayer.feature);
        this.props.onCatchmentClicked(this.selectedLayer);
    },
    determineFeatureMarkers: function() {
        var feature;
        for (var i in this.allFeatures) {
            feature = this.allFeatures[i];
            feature.markers = this.getCatchmentMarkers(feature);
            this.gjLayer.resetStyle(this.allFeatureLayers[i]);
        }
    },
    getCatchmentMarkers: function(feature) {
        var markers = {};
        markers[feature.properties.ES_ID] = this.allMarkers[feature.properties.ES_ID];
        markers[feature.properties.MS_ID] = this.allMarkers[feature.properties.MS_ID];
        markers[feature.properties.HS_ID] = this.allMarkers[feature.properties.HS_ID];

        this.placedMarkers[feature.properties.ES_ID] = 1;
        this.placedMarkers[feature.properties.MS_ID] = 1;
        this.placedMarkers[feature.properties.HS_ID] = 1;

        return markers;
    },
    getActivityHtmlOutput: function(feature) {
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
        $('#activities-list .activity-info').eq(0).html(this.totalActivityCountByFeature(feature));

        var schoolActivityCounts;
        $('#activities-list #activities-details').html('');
        for (var code in feature.markers) {
            if (typeof this.activityCounts[code] != "undefined") {
                var categories = this.activityCounts[code]['categories'];
                if (typeof categories != "undefined") {
                    schoolActivityCounts = this.activityCounts[code];
                    $('#activities-list #activities-details').append("<div class='school-overview' id='school-"+code+"'><h5>"+ this.activityCounts[code].name +" ("+schoolActivityCounts.total+")</h5></div>")
                    var activities = $("<div class='activity-listing'></div>");
                    var key;
                    var output = "";
                    for (var j = 0; j < Object.keys(categories).length; j++) {
                        key = Object.keys(this.activityCounts[code]['categories'])[j];
                        output += "<li>"+key+" <span class='badge'>"+this.activityCounts[code]['categories'][key]+"</span></li>";
                    }
                    activities.html(output);
                    $("#school-"+code).eq(0).append(activities);
                }
            }
        }

        $('#activities-list').show();
    },
    onEachFeature: function(feature, layer) {
        layer.on({
            mouseover: this.highlightFeature,
            mouseout: this.resetHighlight,
            click: this.zoomToFeature
        });

        feature.name = feature.properties.ES_Short+" Catchment Area";
        this.allFeatures.push(feature);
        this.allFeatureLayers.push(layer);
    },
    showMarkersInFeature: function(feature) {
        var mark;
        if (typeof feature.markers != "undefined") {
            for (var code in feature.markers) {
                mark = feature.markers[code];
                this.map.addLayer(mark);
                this.visibleMarkers.push(mark);
            }
        }
    },
    removeVisibleMarkers: function() {
        console.log('removing visible markers', this.visibleMarkers);
        for (var i = 0; i < this.visibleMarkers.length; i++) {
            this.map.removeLayer(this.visibleMarkers[i]);
        }

        this.visibleMarkers = [];
    },
    placeRemainingMarkers: function() {
        var code, mark, markerLayer;
        for (var code in this.allMarkers) {
            if (!(code in this.placedMarkers)) {
                mark = this.allMarkers[code];
                markerLayer = leafletPip.pointInLayer(mark.getLatLng(), this.gjLayer, true);
                if (markerLayer.length) {
                    markerLayer[0].feature.markers[code] = mark;
                    this.placedMarkers[code] = 1;
                    this.gjLayer.resetStyle(markerLayer[0]);
                }
            }
        }
    },
    placeMarker: function(code) {
        var layer;
        for (var i = 0; i < this.allFeatureLayers.length; i++) {
            layer = this.allFeatureLayers[i];
            if (layer.getBounds().contains(this.allMarkers[code].getLatLng())) {
                console.log(this.allMarkers[code].getLatLng(), "Placing marker for: "+this.allSchoolData[code].name+" in '"+layer.feature.name+"'");
                layer.feature.markers[code] = this.allMarkers[code];
                this.placedMarkers[code] = 1;
                this.gjLayer.resetStyle(layer);

                return;
            }
        }
        console.log("Could Not Place Marker for: ",this.allSchoolData[code].name);
    },
    buildMap: function(){
        this.allSchoolData = this.props.activityData;
        this.activityCounts = this.allSchoolData;

        // Add Markers for All Schools -- May be temp...
        var schoolCodes = Object.keys(this.allSchoolData);
        var code, iconColor, lat, lng, icon, mark;
        for (var i = 0; i < schoolCodes.length; i++) {
            code = schoolCodes[i];
            lat = this.allSchoolData[code].latitude;
            lng = this.allSchoolData[code].longitude;

            if (typeof this.activityCounts[code].total == "undefined") {
                iconColor = "#ccc";
            } else if (this.activityCounts[code].total < 3) { //&& this.activityCounts[code].type == "District") {
                iconColor = this.props.iconColors[0];
            // } else if (this.activityCounts[code].type == "District") {
            //     iconColor = "#891e1e";
            } else {
                iconColor = this.props.iconColors[1];;
            }
            icon = L.MakiMarkers.icon({icon: null, color: iconColor, size: "m"});

            mark = L.marker([lat, lng], {icon:icon});
            this.allMarkers[code] = mark;
            var count = typeof this.allSchoolData[code].total == "undefined" ? 0 : this.allSchoolData[code].total;
            mark.bindPopup("<b>"+this.allSchoolData[code].name+" (" + count + ")</b><br>"+this.allSchoolData[code].type+" - "+this.allSchoolData[code].gradeLevel);
        }

        /**
         * NOTE: GeoJSON file successfully converted using:
         * ogr2ogr -f GeoJSON -t_srs crs:84 SDP_Boundaries_1415.geojson SDP_Boundaries_1415.shp
         */
        this.gjLayer = L.geoJson(sdpBoundaries, {
            style: this.style,
            onEachFeature: this.onEachFeature
        });

        this.determineFeatureMarkers();

        // Remaining Schools Also Need Regions
        this.placeRemainingMarkers();

        this.gjLayer.addTo(this.map);
    },
    removeHoverBox: function() {
      if (this.infoDisplayed) {
        this.map.removeControl(this.info);
        this.infoDisplayed = false;
      }
    },
    displayHoverBox: function(){
      if (!this.infoDisplayed) {
        this.map.addControl(this.info);
        this.infoDisplayed = true;
      }
    },
    infoAndZoomControls: function() {
        /** ---------- CATCHMENT REGION INFO ---------- **/
        this.info = L.control({position: 'bottomleft'});

        this.info.onAdd = function (map) {
            this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
            this.update();
            return this._div;
        };

        // Zoom Controls
        var zoomHome = L.Control.zoomHome();
        zoomHome.addTo(this.map);

        // method that we will use to update the control based on feature properties passed
        var self = this;
        self.info.update = function (catchmentName, props, activityCount) {
            if (!catchmentName && !props && typeof self.selectedLayer != "undefined") {
                // Use info from selected layer.
                catchmentName = self.selectedLayer.feature.name;
                props = self.selectedLayer.feature.properties;
                activityCount = self.selectedLayer.feature.activityCount;
                // console.log('hovering on catchment with data: ', self.selectedLayer.feature);
            }
            if (catchmentName && props) {
                var badgeColor = self.getColor(activityCount);
                var output = '<h4><span class="badge" style="background-color:'+badgeColor+'">'+ activityCount +'</span>'+ catchmentName + '</h4>';
                output += "<div><strong>" + self.allSchoolData[props.ES_ID].gradeLevel + "</strong>: " + self.allSchoolData[props.ES_ID].name + "<br></div>";
                if (props.ES_ID != props.MS_ID) {
                    output += "<div><strong>" + self.allSchoolData[props.MS_ID].gradeLevel + "</strong>: " + self.allSchoolData[props.MS_ID].name + "<br></div>";
                }
                if (props.HS_ID != props.MS_ID) {
                    output += "<div><strong>" + self.allSchoolData[props.HS_ID].gradeLevel + "</strong>: " + self.allSchoolData[props.HS_ID].name + "<br></div>";
                }
            } else {
                var output = '<h2>Penn GSE in Philadelphia</h2>Hover over a catchment region. <br>OR <a data-toggle="modal" data-target="#district-projects">View District-Wide Projects</a>';
            }

            // Whether to highlight the box or not.
            if (typeof self.selectedLayer != "undefined" && self.selectedLayer.feature.name == catchmentName) {
                $('.info').addClass('active');
            } else {
                $('.info').removeClass('active');
            }

            this._div.innerHTML = output;
        };

        this.info.addTo(this.map);
        /** --------------------------------------------- **/

        this.map.on('click', function(e){
            if (typeof self.selectedLayer != "undefined") {
                // Reset weight to default
                self.gjLayer.resetStyle(self.selectedLayer);

                for (var i = 0; i < self.selectedCatchmentMarkers.length; i++) {
                    var mark = self.selectedCatchmentMarkers[i];
                    map.removeLayer(mark);
                }
                // Clear out data.
                self.selectedCatchmentMarkers = [];
            }
        });
    },
    render: function() {
        return (
            <div id="map">
            </div>
        );
    }
});

module.exports = MapDisplay;
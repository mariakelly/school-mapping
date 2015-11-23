/**
 * app.js - Main Map Script
 */
/* -------------------------
 * |   require statements  |
 * ------------------------- */
var L           = require('leaflet');
var leafletPip  = require('leaflet-pip');
// var $           = require('jquery');

global.jQuery = require('jquery');

var React       = require('react');
var ReactDOM    = require('react-dom');

// Added locations to package.json as explained here:
//  http://blog.npmjs.org/post/112064849860/using-jquery-plugins-with-npm
require('leaflet.zoomhome');
require('Leaflet.MakiMarkers');
require('bootstrap');

var MappingApp = require('./components/MappingApp.jsx');

ReactDOM.render(
  <MappingApp />,
  document.getElementById('mapping-app')
);
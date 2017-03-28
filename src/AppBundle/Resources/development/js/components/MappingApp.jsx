/**
 * Penn GSE Mapping Project
 */

// import React from 'react';
var React = require('react');
var $ = require('jquery');

var MapDisplay = require('./MapDisplay.jsx');
var CatchmentInfo = require('./CatchmentInfo.jsx');
var SchoolDetails = require('./SchoolDetails.jsx');
var FilterDisplay = require('./FilterDisplay.jsx');
var DistrictProjects = require('./DistrictProjects.jsx');
var SchoolSearch = require('./SchoolSearch.jsx');
var YearSelect = require('./YearSelect.jsx');

var MappingApp = React.createClass({
  getInitialState: function() {
    console.log("hello: ", window.location.hash.substr(1));
    console.log("2016-2017 Data!");

    // Check for Year in Get Params URL
    var requestedYear = getQueryVariable('year');
    var year = (typeof requestedYear == 'undefined') ? '2016-2017' : requestedYear;

    return {
      year: year,
      catchment: null,
      school: null,
      schoolData: null,
      filterDefault: window.location.hash.substr(1),
      categoryFilters: [],
      selectedCategories: [],
      groupFilters: [],
      selectedGroups: [],
      schoolActivityData: null,
      schoolList: [],
      districtProjects: null
    };
  },
  componentDidMount: function() {
    this.colors = ["#f8bcc4", "#f17788", "#ea3142", "#c1292c"];
    this.iconColors = ["#61C7ED", "#009ad4"];

    this.getActivityData(this);
    this.getSchoolList(this);
    this.getFilterOptions(this);
    this.getDistrictProjectData(this);
  },
  displayDistrictProjectsModal: function() {
    $('#district-projects').modal('show');
  },
  getActivityData: function(app) {
    // Markers for all points.
    var url = Routing.generate('school_activity_data')+"?";
    url = url + this.getUrlParams();

    // Don't set this if we might have to filter by default:
    if (app.state.schoolActivityData !== null || (app.state.filterDefault == "" || app.state.selectedCategories.length != 0 || app.state.selectedGroups.length)) {
      $.getJSON(url, function(data){
        app.setState({
          schoolActivityData: data
        });
      });
    }
  },
  getSchoolList: function(app) {
    var url = Routing.generate('school_list');

    $.getJSON(url, function(data){
      app.setState({
        schoolList: data
      });
    });
  },
  getSchoolData: function(app) {
    // Markers for all points.
    var url = Routing.generate('get_activity_counts', { schoolCode: this.state.school });
    url = url + "&" + this.getUrlParams();

    $.getJSON(url, function(data){
      app.setState({
        schoolData: data
      });
    });
  },
  getDistrictProjectData: function(app) {
    // Markers for all points.
    var url = Routing.generate('get_district_projects', { schoolCode: this.state.school });
    url = url + "&" + this.getUrlParams();

    $.getJSON(url, function(data){
      app.setState({
        districtProjects: data
      });
    });
  },
  getUrlParams: function() {
    var params = {};
    params.year = this.state.year;
    if (this.state.selectedCategories.length) {
      params.category = this.state.selectedCategories[0];
    }
    if (this.state.selectedGroups.length) {
      params.group = this.state.selectedGroups[0];
    }

    return $.param(params);
  },
  getFilterOptions: function(app) {
    var url = Routing.generate('map_filters');
    $.getJSON(url, function(data){
      // Set default category if we have one and this is the first load.
      var setCategory = [];
      if (app.state.categoryFilters.length == 0 && app.state.filterDefault != "") {
        for (var i in data['Category']) {
            var category = data['Category'][i];
            console.log("looking to set category ", category.name, app.state.filterDefault);
            if (category.name.toLowerCase().replace(" ", "_") == app.state.filterDefault) {
                setCategory = [category.id];
            }
        }
      }

      // Set default group if we have one and this is the first load
      var setGroup = [];
      if (!setCategory.length && app.state.categoryFilters.length == 0 && app.state.filterDefault != "") {
        for (var i in data['Group']) {
            var group = data['Group'][i];
            console.log("looking to set group ", group.abbreviation, app.state.filterDefault);
            if (group.abbreviation.toLowerCase().replace(" ", "_") == app.state.filterDefault) {
                setGroup = [group.id];
            }
        }
      }

      var schoolActivityData = app.state.schoolActivityData;
      if (setCategory.length) {
        var schoolActivityData = null;
      }

      app.setState({
        categoryFilters: data['Category'],
        groupFilters: data['Group'],
        yearOptions: data['Year'],
        selectedCategories: setCategory,
        selectedGroups: setGroup,
        schoolActivityData: schoolActivityData
      });
    });
  },
  componentDidUpdate: function(prevProps, prevState) {
    console.log('in componentDidUpdate');
    if (
        this.state.selectedCategories != prevState.selectedCategories
         || this.state.selectedGroups != prevState.selectedGroups
         || this.state.year != prevState.year
    ) {
      console.log('updating schoolActivityData');
      this.setState({
        schoolActivityData: null
      });
      this.getActivityData(this);
    }

    if (this.state.school != prevState.school) {
      this.getSchoolData(this);
    }
  },
  shouldComponentUpdate: function(nextProps, nextState) {
    return true;
  },
  onCatchmentSelected: function(selectedFeature) {
    this.refs.map.removeHoverBox();
    this.setState({
      catchment: selectedFeature,
      school: null,
      schoolData: null
    });
  },
  onCatchmentClosed: function() {
    this.refs.map.displayHoverBox();
    this.refs.map.clearSelected();
    if (this.state.school != null) {
      this.setState({
        catchment: null,
        school: null,
        schoolData: null
      });
    }
  },
  onSchoolSelected: function(code) {
    // Set to new school and null out old data
    if (this.state.school != code) {
      this.setState({
        school: code,
        schoolData: null
      });

      // Go to top of school section
      var schoolSectionTop = $('#bottom-region').offset().top;
      $('html, body').animate({
        scrollTop: schoolSectionTop
      }, 800);
    }
  },
  onFilterSelected: function(filterInfo) {
    var type = filterInfo[0];
    var value = filterInfo[1];
    if (type === 'category') {
      this.setState({
        selectedCategories: [value],
        selectedGroups: [],
        catchment: null,
        school: null
      });
    } else if (type === 'group') {
      this.setState({
        selectedCategories: [],
        selectedGroups: [value],
        catchment: null,
        school: null
      });
    }
  },
  onYearSelected: function(yearInfo) {
    // console.log('In onYearSelected', yearInfo);
    this.setState({
        year: yearInfo
    });
  },
  clearFilters: function() {
      this.setState({
        selectedCategories: [],
        selectedGroups: [],
        catchment: null,
        school: null
      });
  },
  getCurrentFilterOption: function() {
    if (this.state.selectedCategories.length) {
      return 'category-'+this.state.selectedCategories[0];
    }
    if (this.state.selectedGroups.length) {
      return 'group-'+this.state.selectedGroups[0];
    }

    return null;
  },
  showSchoolByCode: function(code) {
    var map = this.refs.map;
    map.displayMarkerForSchool(code);
    var catchment = map.findCatchmentForSchool(code);
    map.removeHoverBox();
    this.setState({catchment: catchment});

    // Scroll down, but delay so the pan happens firsts
    var self = this;
    setTimeout(function(){
      self.onSchoolSelected(code);
    }, 600);
  },
  render: function() {
    if (this.state.schoolActivityData === null) {
      return <h3 className="text-center"><br /><br /><br />Loading...</h3>
    }

    var totalActivityCount = 0;
    var totalSchoolCount = 0;
    for (var key in this.state.schoolActivityData) {
        var data = this.state.schoolActivityData[key];
        if (typeof data.total !== "undefined") {
            totalActivityCount += data.total;
            totalSchoolCount += 1;
        }

        // console.log("Showing "+ totalActivityCount + " Activities");
    }

    // console.log('re-rendering app with state', this.state);
    var catchmentName = (this.state.catchment === null) ? null : this.state.catchment.feature.name;

    // School Detail Information
    var schoolInfo = (this.state.schoolActivityData === null || this.state.school === null)
      ? null : this.state.schoolActivityData[this.state.school];
    var schoolDetails = [];
    if (schoolInfo !== null) {
      schoolDetails = (
          <SchoolDetails
            catchmentName={catchmentName}
            code={this.state.school}
            info={schoolInfo}
            data={this.state.schoolData}
            iconColors={this.iconColors}
            yearSelected={this.state.year}>
          </SchoolDetails>
      );
    }

    var showCategoriesInCatchments = false;
    if (this.state.selectedCategories.length === 0 && this.state.selectedCategories.length === 0) {
      showCategoriesInCatchments = true;
    }

    return (
      <div>
        <DistrictProjects
          districtProjects={this.state.districtProjects}>
        </DistrictProjects>
        <div id="top-region">
          <div id="map-display">
            <MapDisplay
              ref="map"
              colors={this.colors}
              iconColors={this.iconColors}
              activityData={this.state.schoolActivityData}
              onCatchmentClicked={this.onCatchmentSelected}>
            </MapDisplay>
            <div id='total-count'>Showing <span className="total-number">{totalActivityCount}</span> Total Activities in <span className="total-number">{totalSchoolCount}</span> Schools</div>
            <SchoolSearch
              selectedFilters={this.getCurrentFilterOption()}
              schools={this.state.schoolList}
              handleSchoolSelected={this.showSchoolByCode}>
            </SchoolSearch>
            <div id="legend">
                <h3>Activities</h3>
                <div className="sub-legend">
                  <div className="legend-header">by Catchment</div>
                  <div><div className="color-legend" style={{backgroundColor:this.colors[0]}}></div>1-3</div>
                  <div><div className="color-legend" style={{backgroundColor:this.colors[1]}}></div>4-6</div>
                  <div><div className="color-legend" style={{backgroundColor:this.colors[2]}}></div>7-10</div>
                  <div><div className="color-legend" style={{backgroundColor:this.colors[3]}}></div>11+</div>
                </div>
                <div className="sub-legend">
                  <div className="legend-header">by School</div>
                  <div><div className="color-legend" style={{backgroundColor:'#ccc'}}></div>0</div>
                  <div><div className="color-legend" style={{backgroundColor:this.iconColors[0]}}></div>1-2</div>
                  <div><div className="color-legend" style={{backgroundColor:this.iconColors[1]}}></div>3+</div>
                </div>
            </div>
            <YearSelect
              selectedFilters={this.getCurrentFilterOption()}
              options={this.state.yearOptions}
              onFilterSelected={this.onYearSelected}
              selected={this.state.year}>
            </YearSelect>
            <FilterDisplay
              categoryOptions={this.state.categoryFilters}
              groupOptions={this.state.groupFilters}
              onFilterSelected={this.onFilterSelected}
              selectedOption={this.getCurrentFilterOption()}
              clearFilters={this.clearFilters}>
            </FilterDisplay>
          </div>
          <CatchmentInfo
            showCategories={showCategoriesInCatchments}
            catchment={this.state.catchment}
            activityCounts={this.state.schoolActivityData}
            onCatchmentClosed={this.onCatchmentClosed}
            onSchoolSelected={this.onSchoolSelected}
            colors={this.colors}
            iconColors={this.iconColors}>
          </CatchmentInfo>
        </div>
        <div id="bottom-region">
          {schoolDetails}
        </div>
      </div>
    );
  }
});

function getQueryVariable(variable) {
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
    if (pair[0] == variable) {
      return pair[1];
    }
  }
  console.log('Query Variable ' + variable + ' not found');
}

module.exports = MappingApp;
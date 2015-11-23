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

var MappingApp = React.createClass({
  getInitialState: function() {
    return {
      year: '2015',
      catchment: null,
      school: null,
      schoolData: null,
      categoryFilters: [],
      selectedCategories: [],
      groupFilters: [],
      selectedGroups: [],
      schoolActivityData: null,
      districtProjects: null
    };
  },
  componentDidMount: function() {
    this.colors = ["#f8bcc4", "#f17788", "#ea3142", "#c1292c"];

    this.getActivityData(this);
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

    $.getJSON(url, function(data){
      app.setState({
        schoolActivityData: data
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

    $.getJSON(url, function(data){
      app.setState({
        districtProjects: data
      });
    });
  },
  getUrlParams: function() {
    var params = {};
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
      app.setState({
        categoryFilters: data['Category'],
        groupFilters: data['Group'],
      });
    });
  },
  componentDidUpdate: function(prevProps, prevState) {
    if (this.state.selectedCategories != prevState.selectedCategories || this.state.selectedGroups != prevState.selectedGroups) {
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
      catchment: selectedFeature
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
  getCurrentFilterOption: function() {
    if (this.state.selectedCategories.length) {
      return 'category-'+this.state.selectedCategories[0];
    }
    if (this.state.selectedGroups.length) {
      return 'group-'+this.state.selectedGroups[0];
    }
  },
  render: function() {
    if (this.state.schoolActivityData === null) {
      return <h3 className="text-center"><br /><br /><br />Loading...</h3>
    }

    // console.log('re-rendering app with state', this.state);

    // School Detail Information
    var schoolInfo = (this.state.schoolActivityData === null || this.state.school === null)
      ? null : this.state.schoolActivityData[this.state.school];
    var schoolDetails = [];
    if (schoolInfo !== null) {
      schoolDetails = (
          <SchoolDetails
            code={this.state.school}
            info={schoolInfo}
            data={this.state.schoolData}>
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
              activityData={this.state.schoolActivityData}
              onCatchmentClicked={this.onCatchmentSelected}>
            </MapDisplay>
            <div id="legend">
                <h3>Legend</h3>
                <div><div className="color-legend" style={{backgroundColor:this.colors[0]}}></div>1-3 activities</div>
                <div><div className="color-legend" style={{backgroundColor:this.colors[1]}}></div>4-6 activities</div>
                <div><div className="color-legend" style={{backgroundColor:this.colors[2]}}></div>7-10 activities</div>
                <div><div className="color-legend" style={{backgroundColor:this.colors[3]}}></div>10+ activities</div>
            </div>
            <FilterDisplay
              categoryOptions={this.state.categoryFilters}
              groupOptions={this.state.groupFilters}
              onFilterSelected={this.onFilterSelected}
              selectedOption={this.getCurrentFilterOption()}>
            </FilterDisplay>
          </div>
          <CatchmentInfo
            showCategories={showCategoriesInCatchments}
            catchment={this.state.catchment}
            activityCounts={this.state.schoolActivityData}
            onCatchmentClosed={this.onCatchmentClosed}
            onSchoolSelected={this.onSchoolSelected}>
          </CatchmentInfo>
        </div>
        <div id="bottom-region">
          {schoolDetails}
        </div>
      </div>
    );
  }
});

module.exports = MappingApp;
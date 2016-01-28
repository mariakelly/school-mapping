/**
 * CatchmentInfo Component
 * -  Displays information at the catchment level
 *    about the ongoing activities.
 */

import React from 'react';

var SchoolOverview = React.createClass({
  onPress: function() {
    this.props.onSchoolSelected(this.props.code);
  },
  render: function() {
    var schoolData = this.props.activityCount;
    var categories = schoolData['categories'];
    var key;
    var activityBreakdown = [];

    var badgeDisplayColor = (schoolData.total < 3) ? this.props.iconColors[0] : this.props.iconColors[1];

    return (
      <div className='school-overview' id={'school-'+this.props.code} onClick={this.onPress}>
        <h5><span className="grade-level">{schoolData.gradeLevel}</span></h5>
        <div className="badge" style={{backgroundColor: badgeDisplayColor}}>{schoolData.total}</div><div className="school-name"><h3>{schoolData.name}</h3></div>
      </div>
    );
  }
});

var CatchmentInfo = React.createClass({
  getInitialState: function() {
    return {
      opened: true
    };
  },
  close: function() {
    this.setState({
      opened: false
    });

    this.props.onCatchmentClosed();
  },
  componentWillUpdate: function(nextProps, nextState) {
    // Any change should re-open this... maybe?
    if (!this.state.opened) {
      this.setState({
        opened: true
      });
    }
  },
  onSchoolSelected: function(code) {
    this.props.onSchoolSelected(code);
  },
  schoolLevelData: function() {
    var schoolListings = [];
    var feature = this.props.catchment.feature;

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

    // Create SchoolOverview Blocks for each with activities
    var schoolActivityCounts;
    for (var code in feature.markers) {
        if (typeof this.props.activityCounts[code] != "undefined" && typeof this.props.activityCounts[code]['total'] != "undefined") {
          schoolListings.push(
            <SchoolOverview
              key={"school-"+code}
              code={code}
              onSchoolSelected={this.onSchoolSelected}
              showCategories={this.props.showCategories}
              activityCount={this.props.activityCounts[code]}
              iconColors={this.props.iconColors}>
            </SchoolOverview>
          );
        }
    }

    // Add Description/Instruction Text.
    if (schoolListings.length) {
      schoolListings = (
        <div>
          <p className="description">Click on a school below for more information.</p>
          {schoolListings}
        </div>
      );
    } else {
      return (
        <div>
          <p className="description">There are no active projects in this catchment.</p>
        </div>
      );
    }

    return schoolListings;
  },
  shouldComponentUpdate: function(nextProps, nextState) {
    if (nextProps.catchment == this.props.catchment && nextState.opened == this.props.opened) {
      return false;
    }

    return true;
  },
  render: function() {
    if (this.props.catchment == null || !this.state.opened) {
      return <div></div>
    }

    // console.log('catchment:',this.props.catchment);

    var activityCount = this.props.catchment.feature.activityCount;
    var schoolListings = this.schoolLevelData();

    var badgeColor = '#ccc';
    if (activityCount > 0 && activityCount <= 3) {
        badgeColor = this.props.colors[0];
    } else if (activityCount > 3 && activityCount <= 6) {
        badgeColor = this.props.colors[1];
    } else if (activityCount > 6 && activityCount <= 10) {
        badgeColor = this.props.colors[2];
    } else if (activityCount > 10) {
        badgeColor = this.props.colors[3];
    }

    console.log(badgeColor);

    return (
      <div id="catchment-display">
        <div className="catchment-display-inner">
          <div className="catchment-display-header row">
            <div className="pull-right close-button"><span className="glyphicon glyphicon-remove" onClick={this.close}></span></div>
            <h4><span className="badge" style={{backgroundColor: badgeColor}}>{activityCount}</span> Active Projects</h4>
            <h2>{this.props.catchment.feature.name}</h2>
          </div>
          <div className="school-listings row">
            {schoolListings}
          </div>
        </div>
      </div>
    );
  }
});

module.exports = CatchmentInfo;
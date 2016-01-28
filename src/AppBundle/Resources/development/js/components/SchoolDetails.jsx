/**
 * SchoolDetails Component
 * -  Displays information at the catchment level
 *    about the ongoing activities.
 */

import React from 'react';

var $ = require('jquery');

var ActivityDisplay = React.createClass({
  render: function() {
    var activity = this.props.activity;

    // Categories
    var categories = [];
    activity.categories.forEach(function(cat, idx){
      categories.push(
        <span key={'category-'+cat}className="activity-category">{cat}</span>
      );
    });

    // People
    var people = [];
    if (activity.people !== null) {
      activity.people.forEach(function(person, idx){
        var display = (person.website !== null) ? (<a target="_blank" href={person.website}>{person.name}</a>) : person.name;
        var seperator = (idx == activity.people.length - 1) ? "" : ", ";
        people.push(
          <span key={person.name} className="activity-person">{display}{seperator}</span>
        );
      });
    }
    if (people.length) {
      people = (
        <div className="activity-people">{people}</div>
      );
    }

    // Groups
    var groups = [];
    if (activity.groups !== null) {
      activity.groups.forEach(function(group, idx){
        var display = (group.website !== null) ? (<a target="_blank" href={group.website}>{group.name}</a>) : group.name;
        var seperator = (idx == activity.groups.length - 1) ? "" : ", ";
        groups.push(
          <span key={group.name} className="activity-group">{display}{seperator}</span>
        );
      });
    }
    if (groups.length) {
      groups = (
        <div className="activity-groups">{groups}</div>
      );
    }

    // Partners
    var partners = null;
    if (activity.partners != null) {
        partners = (
            <div className="activity-partners">
                <p><strong>Partners: {activity.partners}</strong></p>
            </div>
        );
    }

    // Website Link
    var link = null;
    if (activity.website !== null) {
      link = (
        <a target="_blank" className="learn-more" href={activity.website}>Learn More</a>
      );
    }

    // var description = [];
    var description = activity.description.split("\n").map(function(item) {
      return (
        <span key={item}>
          {item}
          <br/>
        </span>
      )
    });

    return (
      <div className="activity-details">
        <h2>{activity.name}</h2>
        <span className="categories">{categories}</span>
        <div className="activity-description">{description}</div>
        {people}
        {groups}
        {link}
      </div>
    );
  }
});

var CategoryActivities = React.createClass({
  render: function() {
    var activities = [];

    this.props.activities.forEach(function(activity, idx){
      activities.push(
        <ActivityDisplay
          key={activity}
          activity={activity}>
        </ActivityDisplay>
      );
    });

    return (
      <div>
        <h3>{this.props.name}</h3>
        {activities}
      </div>
    );
  }
});

var SchoolDetails = React.createClass({
  goToTop: function() {
    $('html, body').animate({
      scrollTop: 0
    }, 800);
  },
  render: function() {
    var name = this.props.info.name;
    var website = this.props.info.website;
    if (website) {
        website = (
            <p className="website-link pull-right"><a target="_blank" href={website}>View School Profile</a></p>
        );
    }

    var count = '';

    var dataDisplay = [];
    if (this.props.data === null) {
      dataDisplay = (
        <h3 className="text-center"><br />Loading...</h3>
      );
    } else {
      var self = this;
      var activityDetails = [];
      this.props.data.forEach(function(activity, idx){
        activityDetails.push(
          <ActivityDisplay
            key={'activity-'+activity.id}
            name={activity.name}
            activity={activity}>
          </ActivityDisplay>
        );
      });

      if (this.props.data.length != 0) {
        // Split this into columns
        var colLength = Math.ceil(this.props.data.length / 2); // two columns
        var firstCol = activityDetails.slice(0,colLength);
        var secondCol = activityDetails.slice(colLength);
        console.log(firstCol, secondCol);
        dataDisplay = (
          <div className="row">
            <a className="to-top" name="Back to Top" onClick={this.goToTop}><span className="arrow-icon"></span></a>
            <div className="col-md-6">
              {firstCol}
            </div>
            <div className="col-md-6">
              {secondCol}
            </div>
          </div>
        );
      } else {
        dataDisplay = (
          <div className="row">
            <div className="col-md-12">
              <h4 className="text-center">There are currently no activities at this school.</h4>
            </div>
          </div>
        );
      }

      // Badge Display
      count = this.props.data.length;
    }

    var badgeDisplayColor = (count < 3) ? this.props.iconColors[0] : this.props.iconColors[1];

    return (
      <div className="container" id="school-details">
          <h1>{this.props.catchmentName}</h1>
          {website}
          <h2><div className="badge" style={{backgroundColor: badgeDisplayColor}}>{count}</div> <div>{name}</div></h2>
          <div className="school-details-inner">
              <h3 className="grade-level">{this.props.info.gradeLevel}</h3>
              <div className="school-details-datadisplay">
                  {dataDisplay}
              </div>
          </div>
      </div>
      );
  }
});

 module.exports = SchoolDetails;
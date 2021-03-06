/**
 * DistrictProjects Component
 */

import React from 'react';

var ProjectListing = React.createClass({
  render: function() {
    var project = this.props.project;

    // Categories
    var categories = [];
    project.categories.forEach(function(cat, idx){
      categories.push(
        <span key={'category-'+cat}className="activity-category">{cat}</span>
      );
    });

    // People
    var people = [];
    if (project.people !== null) {
      project.people.forEach(function(person, idx){
        var display = (person.website !== null) ? (<a target="_blank" href={person.website}>{person.name}</a>) : person.name;
        var seperator = (idx == project.people.length - 1) ? "" : ", ";
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
    if (project.groups !== null) {
      project.groups.forEach(function(group, idx){
        var display = (group.website !== null) ? (<a target="_blank" href={group.website}>{group.name}</a>) : group.name;
        var seperator = (idx == project.groups.length - 1) ? "" : ", ";
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

    // Website Link
    var link = null;
    if (project.website !== null) {
      link = (
        <div className="project-website-link"><a target="_blank" className="learn-more" href={project.website}>Learn More</a></div>
      );
    }

    return (
      <div className="project-listing">
        <h3>{project.name}</h3>
          <span className="categories">{categories}</span>
          <div className="activity-description">
            {project.description}
          </div>
          {people}
          {groups}
          {link}
      </div>
    );
  }
});

var DistrictProjects = React.createClass({
  onPress: function() {
    this.props.onSchoolSelected(this.props.code);
  },
  render: function() {
    var projects = [];
    var dataDisplay = [];

    if (this.props.districtProjects !== null) {
      this.props.districtProjects.forEach(function(project, idx){
        projects.push(
          <ProjectListing
            key={'project-'+project.id}
            project={project}>
          </ProjectListing>
        );
      });

      // Split this into columns
      var colLength = Math.ceil(this.props.districtProjects.length / 2); // two columns
      var firstCol = projects.slice(0,colLength);
      var secondCol = projects.slice(colLength);
      console.log(firstCol, secondCol);
      var dataDisplay = (
        <div className="row">
          <div className="col-md-6">
            {firstCol}
          </div>
          <div className="col-md-6">
            {secondCol}
          </div>
        </div>
      );
    }

    return (
      <div id="district-projects" className="modal fade" role="dialog" aria-labelledby="District-Wide Projects">
        <div className="modal-dialog">
          <div className="modal-content">
            <div className="modal-header">
              <button type="button" style={{fontSize: '30px'}} className="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h2 className="modal-title">District-Wide Initiatives</h2>
            </div>
            <div className="modal-body">
              <p className="description">
                The following Penn GSE initiatives have impact across the entire School District of Philadelphia.
              </p>
              {dataDisplay}
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = DistrictProjects;
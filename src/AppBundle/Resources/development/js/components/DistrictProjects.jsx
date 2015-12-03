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
        people.push(
          <span key={person.name} className="activity-person">{display}</span>
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
        groups.push(
          <span key={group.name} className="activity-group">{display}</span>
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
        <a target="_blank" className="learn-more" href={project.website}>Learn More</a>
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

    if (this.props.districtProjects !== null) {
        this.props.districtProjects.forEach(function(project, idx){
          projects.push(
            <ProjectListing
              key={'project-'+project.id}
              project={project}>
            </ProjectListing>
          );
        });
    }

    return (
      <div id="district-projects" className="modal fade" role="dialog" aria-labelledby="District-Wide Projects">
        <div className="modal-dialog">
          <div className="modal-content">
            <div className="modal-header">
              <button type="button" className="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h2 className="modal-title">District-Wide Projects</h2>
            </div>
            <div className="modal-body">
              <p className="description">
                Below is a listing of Penn GSE projects that have impact across the entire School District of Philadelphia.
              </p>
              <hr />
              {projects}
            </div>
          </div>
        </div>
      </div>
    );
  }
});

module.exports = DistrictProjects;
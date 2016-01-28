/**
 * DistrictProjects Component
 */

import React from 'react';

var SchoolSearchResult = React.createClass({
  onSchoolSelected: function() {
    this.props.handleSchoolSelected(this.props.school.code);
  },
  render: function() {
    var schoolName = this.props.school.name;
    var query = new RegExp("(" + this.props.searchText + ")", "gim");
    var displayName = [];
    var lastIndex = 0;
    var match;
    while (match = query.exec(schoolName)) {
      displayName.push(<span key={schoolName+"-"+lastIndex+"inbetween"}>{schoolName.substring(lastIndex, match.index)}</span>);
      displayName.push(<strong key={schoolName+"-"+match.index+"match"}>{schoolName.substring(match.index, (match.index + this.props.searchText.length))}</strong>);
      lastIndex = match.index + this.props.searchText.length;
    }

    // Add the end of this string
    if (lastIndex != schoolName.length - 1) {
      displayName.push(<span key={schoolName+"-"+lastIndex+"-end"}>{schoolName.substring(lastIndex)}</span>);
    }

    return (
      <div className="school-result-listing" onClick={this.onSchoolSelected}>
        {displayName}
      </div>
    );
  }
});

var SchoolSearch = React.createClass({
  getInitialState: function() {
    var list = [];

    return {
        searchText: "",
        schoolList: list
    };
  },
  handleChange: function() {
    this.setState({
      searchText: this.refs.searchTextInput.value
    });
  },
  handleSubmit: function(e) {
    e.preventDefault();

    // If someone hit "enter" to submit this and there
    // is only one school result, go ahead and show it.
    if (this.results.length == 1) {
      this.onSchoolSelected(this.results[0].props.school.code);
    }
  },
  onSchoolSelected: function(code) {
    // clear search terms
    this.setState({
      searchText: ""
    });
    this.props.handleSchoolSelected(code);
  },
  render: function() {
    this.results = [];
    var school;

    if (this.state.searchText != "" && this.props.schools !== null) {
      for (var code in this.props.schools) {
        school = this.props.schools[code];
        if (school.name.toLowerCase().indexOf(this.state.searchText.toLowerCase()) !== -1) {
          this.results.push(<SchoolSearchResult searchText={this.state.searchText} school={school} key={school.name} handleSchoolSelected={this.onSchoolSelected} />);
        }
      }
    }

    // Base class on whether filters are applied.
    var filtered = (this.props.selectedFilters === null) ? 'unfiltered' : 'filtered';

    return (
      <div id='school-search' className={filtered}>
        <h3>Search for a School</h3>
        <form onSubmit={this.handleSubmit}>
          <input
            type="text"
            placeholder="Begin typing..."
            value={this.state.searchText}
            ref='searchTextInput'
            onChange={this.handleChange} />
        </form>
        <div id='school-search-results'>
          {this.results}
        </div>
      </div>
    );
  }
});

module.exports = SchoolSearch;
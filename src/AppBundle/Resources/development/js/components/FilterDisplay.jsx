/**
 * FilterDisplay Component
 * - Displays the filtering options for the map.
 */

import React from 'react';

var FilterDropdown = React.createClass({
  render: function() {
    var options = [];

    var self = this;
    this.props.options.forEach(function(opt, idx){
      options.push(
        <option key={self.props.type+'-'+opt.id} value={self.props.type+'-'+opt.id}>{opt.name}</option>
      );
    });

    return (
      <select value={this.props.selected} className="filter-dropdown" onChange={this.props.onOptionSelected}>
        <option></option>
        {options}
      </select>
    );
  }
});

var FilterDisplay = React.createClass({
  onFilterChanged: function(e){
    var filter = e.target.value.split("-");
    this.props.onFilterSelected(filter);
  },
  findDataById: function(id, list) {
    for (var i = 0; i < list.length; i++) {
      if (list[i].id === id) {
        return list[i];
      }
    }

    return null;
  },
  getCurrentFilterData: function() {
    if (this.props.selectedOption) {
      var filterValueArr = this.props.selectedOption.split("-");
      var optionsList = [];
      if (filterValueArr.length == 2 && filterValueArr[0] == 'group') {
        this.type = "Group";
        return this.findDataById(filterValueArr[1], this.props.groupOptions);
      } else if (filterValueArr.length == 2 && filterValueArr[0] == 'category') {
        this.type = "Category";
        return this.findDataById(filterValueArr[1], this.props.categoryOptions);
      }
    }

    return null;
  },
  render: function() {
    var filterOptions = [];
    var categoryOptions = [];
    var groupOptions = [];

    var boxClass = this.props.selectedOption ? 'filtered' : 'not-filtered';
    var filterBoxContents = [];
    var filterBoxFiltered = [];
    if (this.props.selectedOption) {
      var currentFilterData = this.getCurrentFilterData();
      filterBoxFiltered = (
        <div className="selected-filter-info">
          <a className="clear-filter" title="Clear Filter" onClick={this.props.clearFilters}>x</a>
          <div className="text-upper">Viewing Activities by {this.type}:</div> <span className="selected-filter-name">{currentFilterData.name}</span>
        </div>
      );
    }

    filterBoxContents = (
      <div className="filter-box-inner">
        <h4>by Group:</h4>
        <FilterDropdown
            type='group'
            options={this.props.groupOptions}
            onOptionSelected={this.onFilterChanged}
            selected={this.props.selectedOption}>
        </FilterDropdown>
        <h4>OR Category:</h4>
        <FilterDropdown
            type='category'
            options={this.props.categoryOptions}
            onOptionSelected={this.onFilterChanged}
            selected={this.props.selectedOption}>
        </FilterDropdown>
      </div>
    );

    return (
      <div>
        <div className={'filter-box '+boxClass}>
          <h3>Filter</h3>
          {filterBoxContents}
        </div>
        {filterBoxFiltered}
      </div>
    );
  }
});

module.exports = FilterDisplay;
/**
 * FilterDisplay Component
 * - Displays the filtering options for the map.
 */

import React from 'react';

var FilterDisplay = React.createClass({
  onFilterChanged: function(e){
    var filter = e.target.value.split("-");
    this.props.onFilterSelected(filter);
  },
  render: function() {
    var filterOptions = [];
    var categoryOptions = [];
    var groupOptions = [];

    var self = this;

    // Category Options
    if (this.props.categoryOptions) {
      this.props.categoryOptions.forEach(function(category, idx){
        categoryOptions.push(
          <option key={'category-'+category.id} value={'category-'+category.id}>{category.name}</option>
        );
      });
      var categoryOptions = (
        <optgroup key='category-optgroup' label='Category'>
          {categoryOptions}
        </optgroup>
      );

      filterOptions.push(categoryOptions);
    }

    // Group Options
    if (this.props.groupOptions) {
      this.props.groupOptions.forEach(function(group, idx){
        groupOptions.push(
          <option key={'group-'+group.id} value={'group-'+group.id}>{group.name}</option>
        );
      });
      var groupOptions = (
        <optgroup key='group-optgroup' label='Group'>
          {groupOptions}
        </optgroup>
      );

      filterOptions.push(groupOptions);
    }

    return (
      <div className='filter-box'>
        <h3>Filter By...</h3>
        <select value={this.props.selectedOption} onChange={this.onFilterChanged} className="form-control">
          <option>Select</option>
          {filterOptions}
        </select>
      </div>
    );
  }
});

module.exports = FilterDisplay;
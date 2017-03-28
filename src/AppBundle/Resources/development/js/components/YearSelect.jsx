/**
 * YearSelect Component
 */

import React from 'react';

var YearSelect = React.createClass({
  handleChange: function(e) {
    console.log(e.target.value);
    var year = e.target.value;
    if (e.target.value == 'All Years') {
      year = 'all';
    }

    this.props.onFilterSelected(year);
  },
  render: function() {
    var options = [];

    var self = this;
    this.props.options.forEach(function(opt, idx){
      var val = (opt.id === "0") ? 'all' : opt.year;
      options.push(
        <option key={'year-'+opt.id} value={val}>{opt.year}</option>
      );
    });

    // Base class on whether filters are applied.
    var filtered = (this.props.selectedFilters === null) ? 'unfiltered' : 'filtered';

    return (
      <div id="year-select" className={"filter-box "+filtered}>
        <h3>Year</h3>
        <div className="filter-box-inner">
          <select value={this.props.selected} className="filter-dropdown" onChange={this.handleChange}>
            {options}
          </select>
        </div>
      </div>
    );
  }
});

module.exports = YearSelect;
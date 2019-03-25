/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Local import
 */
import Card from './Card';

import './catalog.scss';

class Catalog extends React.Component {
  componentDidMount() {
    const { fetchCatalog } = this.props;
    fetchCatalog();
  }

  render() {
    const { catalogList } = this.props;
    return (
      <div className="list">
        {catalogList.count !== 0 && catalogList.map(item => (
          <Card
            key={item.id}
            {...item}
            source="catalog"
          />
        ))}
      </div>
    );
  }
}

Catalog.propTypes = {
  fetchCatalog: PropTypes.func.isRequired,
  catalogList: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.number.isRequired,
    }).isRequired,
  ).isRequired,
};

/**
 * Export
 */
export default Catalog;

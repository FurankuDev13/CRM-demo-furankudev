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

const Catalog = ({ catalogList }) => (
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

Catalog.propTypes = {
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

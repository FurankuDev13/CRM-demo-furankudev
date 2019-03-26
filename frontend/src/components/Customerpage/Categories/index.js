/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';
import { getURL } from 'src/utils/url';


/**
 * Local import
 */
import Card from 'src/components/Customerpage/Catalog/Card';


const Categories = ({ categoryList }) => (
  <div className="list">
    {categoryList.count !== 0 && categoryList.map(item => (
      <Link
        to={getURL('category', item.name)}
        key={item.id}
      >
        <Card
          {...item}
          source="categories"
        />
      </Link>
    ))}
  </div>
);

Categories.propTypes = {
  categoryList: PropTypes.arrayOf(
    PropTypes.shape({
      name: PropTypes.string.isRequired,
      id: PropTypes.number.isRequired,
    }).isRequired,
  ).isRequired,
};

/**
 * Export
 */
export default Categories;

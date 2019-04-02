/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import { withRouter } from 'react-router';

/**
 * Local import
 */
import Card from './Card';


import './catalog.scss';

const Catalog = ({ currentList, category }) => (
  <div>
    {(category !== 'Catalogue complet' && (
      <div id="category-title">Catégorie : {category}</div>
    )) || <div id="category-title">{category}</div>
    }
    <div className="list">
      {currentList.count !== 0 && currentList.map(item => (
        <Card
          key={item.id}
          {...item}
          source="catalog"
        />
      ))}
    </div>
  </div>
);


Catalog.propTypes = {
  currentList: PropTypes.arrayOf(
    PropTypes.shape({
      id: PropTypes.number.isRequired,
    }).isRequired,
  ).isRequired,
  category: PropTypes.string.isRequired,
};

const CatalogWithRouter = withRouter(Catalog);

/**
 * Export
 */
export default CatalogWithRouter;

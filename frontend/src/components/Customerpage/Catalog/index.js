/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import { withRouter } from 'react-router';

/**
 * Local import
 */
import Select from 'src/containers/Select';
import Productdetails from 'src/containers/Productdetails';
import Card from 'src/containers/Card';


import './catalog.scss';

const Catalog = ({
  currentList,
  category,
  productModalIsActive,
  toggleProductModal,
}) => (
  <div>
    <div>
      {(category !== 'Catalogue complet' && (
        <div id="category-title">Catégorie : {category}</div>
      )) || <div id="category-title">{category}</div>
      }
      <div>Tri des articles
        <Select
          formOrigin="articleOrder"
          name="articleSelect"
          id="article-select"
          options={['Ordre alphabetique', 'Ordre alphabetique inverse', 'Par prix croissant', 'Par prix décroissant']}
        />
      </div>
    </div>
    <div className="list">
      {currentList.count !== 0 && currentList.map(item => (
        <Card
          key={item.id}
          {...item}
          source="catalog"
        />
      ))}
    </div>
    <div className={classNames(
      'modal',
      { 'is-active': productModalIsActive },
    )}
    >
      <div className="modal-background" onClick={toggleProductModal} />
      <Productdetails />
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
  productModalIsActive: PropTypes.bool.isRequired,
  toggleProductModal: PropTypes.func.isRequired,
};

const CatalogWithRouter = withRouter(Catalog);

/**
 * Export
 */
export default CatalogWithRouter;

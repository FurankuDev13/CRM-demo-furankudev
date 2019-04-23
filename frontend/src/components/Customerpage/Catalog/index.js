/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import { withRouter } from 'react-router';
import {
  FaList,
} from 'react-icons/fa';

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
        <div className="has-text-weight-bold" id="category-title">Catégorie : <span className="button is-primary is-size-5 has-text-weight-bold">{category}</span></div>
      )) || <div className="has-text-weight-bold" id="category-title"><FaList />&nbsp;  {category}</div>
      }
      <div>
        <Select
          formOrigin="articleOrder"
          name="articleSelect"
          id="article-select"
          label="Tri des articles"
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

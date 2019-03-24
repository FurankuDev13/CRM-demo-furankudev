/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Local import
 */

const Catalog = ({ name, listPrice, picture }) => (
  <div className="card">
    <div className="card-image">
      <figure className="image is-4by3">
        <img src={picture} alt={name} />
      </figure>
    </div>
    <div className="card-content">
      <div className="media">
        <div className="media-content">
          <p className="title is-4">{name}</p>
          <p className="subtitle is-4">Prix : {listPrice} €</p>
        </div>
        <div className="media-right button is-light">
          <span>+</span>
        </div>
      </div>
    </div>
  </div>
);

Catalog.propTypes = {
  name: PropTypes.string.isRequired,
  listPrice: PropTypes.number.isRequired,
  picture: PropTypes.string.isRequired,
};

/**
 * Export
 */
export default Catalog;

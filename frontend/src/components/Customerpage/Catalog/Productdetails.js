/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Local import
 */

const Productdetails = ({
  description,
  listPrice,
  name,
  picture,
  reference,
}) => (
  <div id="product-modal" className="modal-content columns">
    <div className="column">
      <figure className="image is-4by3">
        <img src={picture} alt={name} />
      </figure>
    </div>
    <div className="column">
      <p className="title">{name}</p>
      <p className="title">Description</p>
      <p>Réference: {reference}</p>
      <p className="subtitle">{description}</p>
      <p>Prix de vente : {listPrice} €</p>
    </div>
  </div>
);

Productdetails.propTypes = {
  description: PropTypes.string.isRequired,
  listPrice: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  picture: PropTypes.string.isRequired,
  reference: PropTypes.string.isRequired,
};

/**
 * Export
 */
export default Productdetails;

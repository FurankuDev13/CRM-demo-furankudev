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
  <div id="product-modal" className="modal-content columns has-background-white is-mobile is-centered has-text-centered">
    <div className="column is-4">
      <figure className="image is-4by3">
        <img src={picture} alt={name} />
      </figure>
    </div>
    <div className="column is-4">
      <p className="button title is-medium is-primary is-size-3 has-text-weight-bold is-spaced">{name}</p>
      <br />
      <p className="title is-4 has-text-primary has-text-weight-bold is-spaced">Description</p>
      <p className="subtitle is-4"><span className="has-text-weight-bold">Référence: </span> {reference}</p>
      <p className="subtitle is-5 is-italic">{description}</p>
      <p className="subtitle is-4"><span className="has-text-weight-bold">Tarif: </span>{listPrice} €</p>
    </div>
  </div>
);

Productdetails.propTypes = {
  description: PropTypes.string.isRequired,
  listPrice: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  picture: PropTypes.string.isRequired,
  reference: PropTypes.string.isRequired,
};

/**
 * Export
 */
export default Productdetails;

/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import {
  FaEuroSign,
} from 'react-icons/fa';

/**
 * Local import
 */

const Card = ({
  name,
  listPrice,
  picture,
  source,
  description,
  displayItem,
}) => (
  <div className="card">
    <div className="card-image">
      <figure className="image is-4by3">
        <img src={picture} alt={name} />
      </figure>
    </div>
    <div className="card-content">
      <div className="media">
        <div className="media-content">
          <p className="title is-4 has-text-primary has-text-weight-bold">{name}</p>
          {source === 'catalog' && (
            <p className="subtitle is-5"><span className="has-text-weight-bold">Tarif: </span>{listPrice} €</p>
          )}
        </div>
        {source === 'catalog' && (
          <div className="media-right button is-light" onClick={displayItem}>
            <span>+</span>
          </div>
        )}
      </div>
      {source === 'categories' && (
        <div className="content is-italic">
          {description}
        </div>
      )}
    </div>
  </div>
);

Card.propTypes = {
  name: PropTypes.string.isRequired,
  description: PropTypes.string.isRequired,
  listPrice: PropTypes.number,
  picture: PropTypes.string.isRequired,
  source: PropTypes.string.isRequired,
  displayItem: PropTypes.func,
};

Card.defaultProps = {
  listPrice: null,
  displayItem: null,
};
/**
 * Export
 */
export default Card;

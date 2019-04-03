/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

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
          <p className="title is-4">{name}</p>
          {source === 'catalog' && (
            <p className="subtitle is-4">Prix : {listPrice} €</p>
          )}
        </div>
        {source === 'catalog' && (
          <div className="media-right button is-light" onClick={displayItem}>
            <span>+</span>
          </div>
        )}
      </div>
      {source === 'category' && (
        <div className="content">
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
  displayItem: PropTypes.func.isRequired,
};

Card.defaultProps = {
  listPrice: null,
};
/**
 * Export
 */
export default Card;

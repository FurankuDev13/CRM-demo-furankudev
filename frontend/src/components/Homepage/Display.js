/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * local import
 */

/**
 * Code
 */

const Display = ({ classes, name, picture }) => (
  <article className={classes}>
    <p className="title">{name}</p>
    <p className="subtitle">Sélection</p>
    <figure className="image is-4by3">
      <img src={picture} alt={name} />
    </figure>
  </article>
);

Display.propTypes = {
  classes: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  picture: PropTypes.string.isRequired,
};

/**
 * Export
 */
export default Display;

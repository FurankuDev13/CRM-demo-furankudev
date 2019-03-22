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

const Input = ({ name, value }) => (
  <div className="field">
    <label className="label" htmlFor={name}>{name}
      <div className="control has-icons-left has-icons-right">
        <input id={name} className="input is-secondary" type={name} placeholder="Entrez votre adresse mail" value={value} />
      </div>
    </label>
  </div>
);

Input.propTypes = {
  name: PropTypes.string.isRequired,
  value: PropTypes.string.isRequired,
};

/**
 * Export
 */
export default Input;

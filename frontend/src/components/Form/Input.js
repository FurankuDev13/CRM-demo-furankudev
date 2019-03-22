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

class Input extends React.Component {
  handleChange = (evt) => {
    const { name, formOrigin, inputChange } = this.props;
    const { value } = evt.target;
    console.log(value);
    inputChange(
      {
        name,
        value,
        formOrigin,
      },
    );
  }

  render() {
    const { name, value } = this.props;
    return (
      <div className="field">
        <label className="label" htmlFor={name}>{name}
          <div className="control has-icons-left has-icons-right">
            <input
              id={name}
              className="input is-secondary"
              type={name}
              placeholder="Entrez votre adresse mail"
              value={value}
              onChange={this.handleChange}
            />
          </div>
        </label>
      </div>
    );
  }
}

Input.propTypes = {
  name: PropTypes.string.isRequired,
  value: PropTypes.string.isRequired,
  formOrigin: PropTypes.string.isRequired,
  inputChange: PropTypes.func.isRequired,
};

/**
 * Export
 */
export default Input;

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

class Select extends React.Component {
  handleChange = (evt) => {
    const { inputChange } = this.props;
    const { value } = evt.target;
    inputChange(value);
  }

  render() {
    const { value } = this.props;
    return (
      <div className="field">
        <label
          className="label"
          htmlFor="select"
        >Nature de votre question
          <div className="control">
            <div
              id="select"
              className="select"
            >
              <select
                value={value}
                onChange={this.handleChange}
              >
                <option>Demande d'information</option>
                <option>Demande de devis</option>
              </select>
            </div>
          </div>
        </label>
      </div>
    );
  }
}

Select.propTypes = {
  value: PropTypes.string.isRequired,
  inputChange: PropTypes.func.isRequired,
};

/**
 * Export
 */
export default Select;

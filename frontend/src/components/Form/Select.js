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
              onChange={this.handleChange}
            >
              <select>
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
  inputChange: PropTypes.func.isRequired,
};

/**
 * Export
 */
export default Select;

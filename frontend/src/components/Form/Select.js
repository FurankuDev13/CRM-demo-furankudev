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
    const {
      value,
      id,
      label,
      options,
    } = this.props;

    return (
      <div className="field">
        <label
          className="label"
          htmlFor={id}
        >{label}
          <div className="control">
            <div
              id={id}
              className="select"
            >
              <select
                value={value}
                onChange={this.handleChange}
              >
                {options.map(option => <option key={option}>{option}</option>)}
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
  id: PropTypes.string.isRequired,
  inputChange: PropTypes.func.isRequired,
  options: PropTypes.arrayOf(
    PropTypes.string.isRequired,
  ).isRequired,
  label: PropTypes.string,
};

Select.defaultProps = {
  label: '',
};

/**
 * Export
 */
export default Select;

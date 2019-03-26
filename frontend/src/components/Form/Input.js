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
    const { inputChange } = this.props;
    const { value } = evt.target;
    inputChange(value);
  }

  render() {
    const {
      label,
      name,
      type,
      element,
      value,
      placeholder,
    } = this.props;
    return (
      <div className="field">
        {element === 'input' && (
          <label className="label" htmlFor={name}>{label}
            <div className="control has-icons-left has-icons-right">
              <input
                id={name}
                className="input is-secondary"
                type={type}
                name={name}
                placeholder={placeholder}
                value={value}
                onChange={this.handleChange}
              />
            </div>
          </label>
        )}
        {element === 'textarea' && (
          <label className="label" htmlFor={name}>{label}
            <div className="control">
              <textarea
                id={name}
                className="textarea"
                type={type}
                name={name}
                placeholder={placeholder}
                value={value}
                onChange={this.handleChange}
              />
            </div>
          </label>
        )}
      </div>
    );
  }
}

Input.propTypes = {
  label: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  type: PropTypes.string.isRequired,
  element: PropTypes.string,
  placeholder: PropTypes.string.isRequired,
  value: PropTypes.string.isRequired,
  inputChange: PropTypes.func.isRequired,
};

Input.defaultProps = {
  element: 'input',
};

/**
 * Export
 */
export default Input;


/* <div>
    <div className='
    ' <label className="label">Name</label>
      <div className="control">
        <input className="input" type="text" placeholder="Text input" />
      </div>
    </div>

    <div className="field">
      <label className="label">Username</label>
      <div className="control has-icons-left has-icons-right">
        <input className="input is-success" type="text" placeholder="Text input" value="bulma" />
        <span className="icon is-small is-left">
          <i className="fas fa-user"></i>
        </span>
        <span className="icon is-small is-right">
          <i className="fas fa-check"></i>
        </span>
      </div>
      <p className="help is-success">This username is available</p>
    </div>

    <div className="field">
      <label className="label">Email</label>
      <div className="control has-icons-left has-icons-right">
        <input className="input is-danger" type="email" placeholder="Email input" value="hello@" />
        <span className="icon is-small is-left">
          <i className="fas fa-envelope"></i>
        </span>
        <span className="icon is-small is-right">
          <i className="fas fa-exclamation-triangle"></i>
        </span>
      </div>
      <p className="help is-danger">This email is invalid</p>
    </div>

    <div className="field">
      <label className="label">Subject</label>
      <div className="control">
        <div className="select">
          <select>
            <option>Select dropdown</option>
            <option>With options</option>
          </select>
        </div>
      </div>
    </div>

    <div className="field">
      <label className="label">Message</label>
      <div className="control">
        <textarea className="textarea" placeholder="Textarea"></textarea>
      </div>
    </div>

    <div className="field">
      <div className="control">
        <label className="checkbox">
          <input type="checkbox" />
          I agree to the <a href="#">terms and conditions</a>
        </label>
      </div>
    </div>

    <div className="field">
      <div className="control">
        <label className="radio">
          <input type="radio" name="question" />
          Yes
        </label>
        <label className="radio">
          <input type="radio" name="question" />
          No
        </label>
      </div>
    </div>

    <div className="field is-grouped">
      <div className="control">
        <button className="button is-link">Submit</button>
      </div>
      <div className="control">
        <button className="button is-text">Cancel</button>
      </div>
    </div>
  </div> */

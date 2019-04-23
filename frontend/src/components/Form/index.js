/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * local import
 */
import Input from 'src/containers/Input';
import Select from 'src/containers/Select';

/**
 * Code
 */

class Form extends React.Component {
  formSubmit = (evt) => {
    const { submitForm } = this.props;
    evt.preventDefault();
    submitForm();
  }

  render() {
    const { tabl, formOrigin, children } = this.props;
    return (
      <form
        id={formOrigin}
        onSubmit={this.formSubmit}
      >
        {tabl.map(inputEntity => (
          <Input
            key={inputEntity.name}
            {...inputEntity}
            formOrigin={formOrigin}
          />
        ))}
        {formOrigin === 'question' && (
          <Select
            formOrigin="question"
            name="questionSelect"
            id="question-select"
            label="Nature de votre question"
            options={['Demande d\'information', 'Demande de devis']}
          />
        )}
        <div className="field is-grouped">
          <div className="control">
            <button
              type="submit"
              className="button is-link"
            >
              Soumettre
            </button>
          </div>
        </div>
        {children}
      </form>
    );
  }
}

Form.propTypes = {
  tabl: PropTypes.array.isRequired,
  formOrigin: PropTypes.string.isRequired,
  submitForm: PropTypes.func.isRequired,
  children: PropTypes.node,
};

Form.defaultProps = {
  children: null,
};

/**
 * Export
 */
export default Form;


/* <div>
    <div className="field">
      <label className="label">Name</label>
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

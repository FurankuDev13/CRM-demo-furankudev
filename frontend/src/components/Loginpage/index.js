/**
 * NPM import
 */
import React from 'react';

/**
 * local import
 */
import Form from 'src/containers/Form';
import PropTypes from 'prop-types';
import { deleteNotification } from 'src/store/reducer';

/**
 * Code
 */

const Login = ({ formErrors, loginFields }) => {
  const { email, password } = loginFields;
  const tabl = [
    {
      name: 'email',
      label: 'Email',
      type: 'email',
      value: email,
      placeholder: 'Saisissez votre email',
    },
    {
      name: 'password',
      label: 'Mot de passe',
      type: 'password',
      value: password,
      placeholder: 'Saisissez votre mot de passe',
    },
  ];
  return (
    <div className="container" id="form-container">
      <div id="notification" className="notification is-danger is-hidden">
        <button
          type="button"
          className="delete"
          onClick={deleteNotification}
        />
        {formErrors[0]}
      </div>
      <Form
        tabl={tabl}
        formOrigin="login"
      />
    </div>
  );
};

Login.propTypes = {
  formErrors: PropTypes.arrayOf(
    PropTypes.string.isRequired,
  ).isRequired,
  loginFields: PropTypes.shape({
    email: PropTypes.string.isRequired,
    password: PropTypes.string.isRequired,
  }).isRequired,
};

/**
 * Export
 */
export default Login;

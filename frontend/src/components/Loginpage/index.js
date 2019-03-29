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

const Login = ({ loginFields }) => {
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
      label: 'mot de passe',
      type: 'password',
      value: password,
      placeholder: 'Saisissez votre mot de passe',
    },
  ];
  return (
    <div>
      <div id="notification" className="notification is-danger is-hidden">
        <button
          type="button"
          className="delete"
          onClick={deleteNotification}
        />
        Vous n'êtes pas enregistré ou vous avez mal saisi votre mot de passe
      </div>
      <Form
        tabl={tabl}
        formOrigin="login"
      />
    </div>
  );
};

Login.propTypes = {
  loginFields: PropTypes.shape({
    email: PropTypes.string.isRequired,
    password: PropTypes.string.isRequired,
  }).isRequired,
};

/**
 * Export
 */
export default Login;

/**
 * NPM import
 */
import React from 'react';

/**
 * local import
 */
import Form from 'src/containers/Form';
import PropTypes from 'prop-types';

/**
 * Code
 */

const Login = ({ loginFields, formOrigin }) => {
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
      <Form
        tabl={tabl}
        formOrigin={formOrigin}
      />
    </div>
  );
};

Login.propTypes = {
  loginFields: PropTypes.shape({
    email: PropTypes.string.isRequired,
    password: PropTypes.string.isRequired,
  }).isRequired,
  formOrigin: PropTypes.string.isRequired,
};

/**
 * Export
 */
export default Login;

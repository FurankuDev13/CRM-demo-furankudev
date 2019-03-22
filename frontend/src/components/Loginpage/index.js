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
  const tabl = [
    {
      name: 'email',
      type: 'email',
      value: loginFields.email,
      placeholder: 'Saisissez votre email',
    },
    {
      name: 'password',
      type: 'password',
      value: loginFields.password,
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

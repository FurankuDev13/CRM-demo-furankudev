/**
 * NPM import
 */
import React from 'react';

/**
 * local import
 */
import Form from 'src/components/Form';
import PropTypes from 'prop-types';

/**
 * Code
 */

const Login = ({ loginFields, formOrigin }) => {
  const tabl = [
    {
      name: 'email',
      value: loginFields.email,
    },
    {
      name: 'password',
      value: loginFields.password,
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

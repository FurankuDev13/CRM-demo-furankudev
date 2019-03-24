/* /**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * local import
 */
import Form from 'src/containers/Form';

/**
 * Code
 */

const Signup = ({ signupFields, formOrigin }) => {
  const {
    companyName,
    companySiren,
    companyAddressField,
    companyPostalCode,
    companyCity,
    contactLastname,
    contactFirstname,
    contactBusinessPhone,
    contactEmail,
    contactPassword,
    contactPasswordRepeat,
    contactRequest,
  } = signupFields;

  const tabl = [
    {

      name: 'companyName',
      label: 'Nom de l\'entreprise',
      type: 'text',
      value: companyName,
      placeholder: 'Saisissez le nom de votre entreprise',
    },
    {
      name: 'companySiren',
      label: 'Siren de la companie',
      type: 'text',
      value: companySiren,
      placeholder: 'Saisissez le SIREN de votre entreprise',
    },
    {
      name: 'companyAddressField',
      label: 'Adresse de l\'entreprise',
      type: 'text',
      value: companyAddressField,
      placeholder: 'Saisissez l\'adresse de votre entreprise',
    },
    {
      name: 'companyPostalCode',
      label: 'code postal',
      type: 'number',
      value: companyPostalCode,
      placeholder: 'Saisissez le code postal de votre entreprise',
    },
    {
      name: 'companyCity',
      label: 'Ville',
      type: 'text',
      value: companyCity,
      placeholder: 'Saisissez la ville où se situe votre entreprise',
    },
    {
      name: 'contactFirstname',
      label: 'Prénom',
      type: 'text',
      value: contactFirstname,
      placeholder: 'Saisissez votre prénom',
    },
    {
      name: 'contactLastname',
      label: 'Nom de famille',
      type: 'text',
      value: contactLastname,
      placeholder: 'Saisissez votre nom de famille',
    },
    {
      name: 'contactBusinessPhone',
      label: 'Numéro de téléphone',
      type: 'number',
      value: contactBusinessPhone,
      placeholder: 'Saisissez votre numéro de téléphone',
    },
    {
      name: 'contactEmail',
      label: 'email',
      type: 'email',
      value: contactEmail,
      placeholder: 'Saisissez votre mot de passe',
    },
    {
      name: 'contactPassword',
      label: 'password',
      type: 'password',
      value: contactPassword,
      placeholder: 'Saisissez votre mot de passe',
    },
    {
      name: 'contactPasswordRepeat',
      label: 'passwordRepeat',
      type: 'email',
      value: contactPasswordRepeat,
      placeholder: 'Confirmez votre mot de passe',
    },
    {
      name: 'contactRequest',
      label: 'contactRequest',
      type: 'email',
      value: contactRequest,
      placeholder: 'Saisissez votre email',
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

Signup.propTypes = {
  signupFields: PropTypes.shape({
    companyName: PropTypes.string.isRequired,
    companySiren: PropTypes.string.isRequired,
    companyAddressField: PropTypes.string.isRequired,
    companyPostalCode: PropTypes.string.isRequired,
    companyCity: PropTypes.string.isRequired,
    contactLastname: PropTypes.string.isRequired,
    contactFirstname: PropTypes.string.isRequired,
    contactBusinessPhone: PropTypes.string.isRequired,
    contactEmail: PropTypes.string.isRequired,
    contactPassword: PropTypes.string.isRequired,
    contactPasswordRepeat: PropTypes.string.isRequired,
    contactRequest: PropTypes.string.isRequired,
  }).isRequired,
  formOrigin: PropTypes.string.isRequired,
};


/**
 * Export
 */
export default Signup;

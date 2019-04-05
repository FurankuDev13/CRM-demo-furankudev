/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * local import
 */
import Form from 'src/containers/Form';
import { deleteNotification } from 'src/store/reducer';

/**
 * Code
 */

const ProfileForm = ({ profileFields, toggleProfileModal, formErrors }) => {
  const {
    firstname,
    lastname,
    businessPhone,
    cellPhone,
    password,
    email,
    passwordRepeat,
  } = profileFields;
  const tabl = [
    {
      name: 'firstname',
      label: 'Prénom',
      type: 'text',
      value: firstname,
      placeholder: '',
    },
    {
      name: 'lastname',
      label: 'Nom de famille',
      type: 'text',
      value: lastname,
      placeholder: '',
    },
    {
      name: 'businessPhone',
      label: 'Numéro de téléphone professionnel',
      type: 'number',
      value: businessPhone,
      placeholder: '',
    },
    {
      name: 'cellPhone',
      label: 'Numéro de portable',
      type: 'number',
      value: cellPhone,
      placeholder: '',
    },
    {
      name: 'email',
      label: 'email',
      type: 'email',
      value: email,
      placeholder: '',
    },
    {
      name: 'password',
      label: 'mot de passe',
      type: 'password',
      value: password,
      placeholder: 'Saisissez votre mot de passe',
    },
    {
      name: 'passwordRepeat',
      label: 'confirmation mot de passepasswordRepeat',
      type: 'password',
      value: passwordRepeat,
      placeholder: 'Confirmez votre mot de passe',
    },
  ];
  return (
    <div className="modal-content">
      <Form
        tabl={tabl}
        formOrigin="profile"
      >
        <button
          id="profile-toggle"
          type="button"
          className="button is-danger modal-close"
          onClick={toggleProfileModal}
        />
        <div id="notification" className="notification is-danger is-hidden">
          <button
            type="button"
            className="delete"
            onClick={deleteNotification}
          />
          <ul>
            {formErrors.map(error => (
              <li key={error}>
                <p>
                  {error}
                </p>
              </li>
            ))}
          </ul>
        </div>
      </Form>
    </div>
  );
};

ProfileForm.propTypes = {
  profileFields: PropTypes.shape({
    firstname: PropTypes.string.isRequired,
    lastname: PropTypes.string.isRequired,
    businessPhone: PropTypes.string.isRequired,
    cellPhone: PropTypes.string.isRequired,
    password: PropTypes.string.isRequired,
    email: PropTypes.string.isRequired,
    passwordRepeat: PropTypes.string.isRequired,
  }).isRequired,
  toggleProfileModal: PropTypes.func.isRequired,
  formErrors: PropTypes.arrayOf(
    PropTypes.string.isRequired,
  ).isRequired,
};

/**
 * Export
 */
export default ProfileForm;

/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

/**
 * local import
 */
import ProfileForm from 'src/containers/ProfileForm';
import './Profilepage.scss';

/**
 * Code
 */

const Profilepage = ({
  firstname,
  lastname,
  cellPhone,
  businessPhone,
  description,
  name,
  picture,
  sirenNumber,
  profileModalIsActive,
  toggleProfileModal,
}) => (
  <div id="profilePage" className="columns is-gapless is-spaced">
    <div className="column is-half">
      <h2>Mes informations</h2>
      <p><span>Prénom : </span>{firstname}</p>
      <p><span>Nom : </span>{lastname}</p>
      <p><span> Numéro de portable : </span>{cellPhone}</p>
      <p><span>Numéro professionnel : </span>{businessPhone}</p>
      <button className="level-item has-text-centered button is-primary is-outlined is-medium" type="button" onClick={toggleProfileModal}>
        Modifier mes informations
      </button>
    </div>
    <div className="column is-half">
      <h2>Infos sur ma société</h2>
      <p><span>Nom : </span>{name}</p>
      <p><span>Notre devise : </span>{description}</p>
      <p><span>Numéro de Siren : </span>{sirenNumber}</p>
      <img src={picture} alt="Logo entreprise" />
    </div>
    <div className={classNames(
      'modal',
      { 'is-active': profileModalIsActive },
    )}
    >
      <div className="modal-background" onClick={toggleProfileModal} />
      <ProfileForm />
    </div>
  </div>
);


Profilepage.propTypes = {
  firstname: PropTypes.string.isRequired,
  lastname: PropTypes.string.isRequired,
  cellPhone: PropTypes.string.isRequired,
  businessPhone: PropTypes.string.isRequired,
  description: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  picture: PropTypes.string.isRequired,
  sirenNumber: PropTypes.string.isRequired,
  profileModalIsActive: PropTypes.bool.isRequired,
  toggleProfileModal: PropTypes.func.isRequired,
};

/**
 * Export
 */
export default Profilepage;

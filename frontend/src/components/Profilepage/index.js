/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import {
  FaUserAlt,
  FaLocationArrow,
  FaPhoneSquare,
  FaRegBuilding,
  FaMapSigns,
  FaIdBadge,
} from 'react-icons/fa';

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
  email,
  cellPhone,
  businessPhone,
  description,
  name,
  picture,
  sirenNumber,
  profileModalIsActive,
  toggleProfileModal,
}) => (
  <div id="profilePage" className="tile is-ancestor is-size-4 is-bold has-text-centered">
    <div className="tile is-child box">
      <h2 className="title is-2 has-text-primary has-text-weight-bold is-spaced">Mes informations</h2>
      <p className="subtitle is-4"><span className="has-text-weight-bold"><FaUserAlt />&nbsp;Prénom: </span>{firstname}</p>
      <p className="subtitle is-4"><span className="has-text-weight-bold"><FaUserAlt />&nbsp;Nom: </span>{lastname}</p>
      <p className="subtitle is-4"><span className="has-text-weight-bold"><FaLocationArrow />&nbsp;email: </span>{email}</p>
      <p className="subtitle is-4"><span className="has-text-weight-bold"><FaPhoneSquare />&nbsp;Numéro de portable: </span>{cellPhone}</p>
      <p className="subtitle is-4"><span className="has-text-weight-bold"><FaPhoneSquare />&nbsp;Numéro professionnel: </span>{businessPhone}</p>
      <button className="has-text-centered button is-primary is-outlined is-medium" type="button" onClick={toggleProfileModal}>
        Modifier mes informations
      </button>
    </div>
    <div className="tile is-child has-background-white box">
      <h2 className="title is-2 has-text-primary has-text-weight-bold is-spaced">Infos sur ma société</h2>
      <p className="subtitle is-4"><span className="has-text-weight-bold"><FaRegBuilding />&nbsp;Nom: </span>{name}</p>
      <p className="subtitle is-4"><span className="has-text-weight-bold"><FaMapSigns />&nbsp;Notre devise : </span>{description}</p>
      <p className="subtitle is-4"><span className="has-text-weight-bold"><FaIdBadge />&nbsp;Numéro de Siren : </span>{sirenNumber}</p>
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
  email: PropTypes.string.isRequired,
  cellPhone: PropTypes.string,
  businessPhone: PropTypes.string.isRequired,
  description: PropTypes.string,
  name: PropTypes.string.isRequired,
  picture: PropTypes.string,
  sirenNumber: PropTypes.string.isRequired,
  profileModalIsActive: PropTypes.bool.isRequired,
  toggleProfileModal: PropTypes.func.isRequired,
};

Profilepage.defaultProps = {
  cellPhone: 'Non renseigné',
  description: 'Non rensegné',
  picture: 'https://cdn.dribbble.com/users/612987/screenshots/4309700/cerberus-logo.jpg',
};

/**
 * Export
 */
export default Profilepage;

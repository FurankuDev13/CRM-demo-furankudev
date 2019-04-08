/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import {
  FaUserTie,
  FaLocationArrow,
  FaPhoneSquare,
} from 'react-icons/fa';

/**
 * local import
 */
import Companyaddress from 'src/components/Homepage/Companyaddress';

/**
 * Code
 */

const Contactpage = ({ commercial }) => (
  <div className="is-size-4 is-bold has-text-centered">
    {
      (Object.prototype.hasOwnProperty.call(commercial, 'firstname') && (
        <div className="box">
          <h2 className="title is-2 has-text-primary has-text-weight-bold">Votre commercial attitré est :</h2>
          <p className="subtitle is-3 has-text-weight-bold"><FaUserTie />&nbsp;{commercial.firstname} {commercial.lastname}</p>
          <p className="subtitle is-4"><span className="has-text-weight-bold"><FaPhoneSquare />&nbsp;télephone : </span>{commercial.businessPhone}</p>
          <p className="subtitle is-4"><span className="has-text-weight-bold"><FaLocationArrow />&nbsp;email : </span>{commercial.email}</p>
        </div>
      )) || <div className="title is-2 has-text-primary has-text-weight-bold">Vous n'avez pas encore de commercial attitré.</div>
    }
    <Companyaddress />
  </div>
);

Contactpage.propTypes = {
  commercial: PropTypes.object.isRequired,
};

/**
 * Export
 */
export default Contactpage;

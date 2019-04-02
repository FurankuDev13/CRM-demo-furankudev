/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

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
        <div>
          <h2 className="title">Votre commercial attitré est :</h2>
          <p>{commercial.firstname} {commercial.lastname}</p>
          <h2 className="title">Comment contacter mon commercial :</h2>
          <p>télephone : {commercial.businessPhone}</p>
          <p>email : {commercial.email}</p>
        </div>
      )) || <div>Vous n'avez pas encore de commercial attitré.</div>
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

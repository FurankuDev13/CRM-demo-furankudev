/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * local import
 */

/**
 * Code
 */

const CompanyAddress = ({ classes, name, picture }) => (
  <section className="hero is-normal is-bold has-text-centered">
    <div className="hero-body">
      <div className="container">
        <h2 className="title is-2 is-spaced has-text-primary is-uppercase has-text-weight-bold">
          Nous contacter
        </h2>
        <p className="title is-3 has-text-weight-bold">
          o'Wine'rs
        </p>
        <p className="is-size-4">10 rue de Penthievre</p>
        <p className="is-size-4">75008 Paris</p>
        <p className="is-size-4">hey@oclock.io</p>
        <p className="is-size-4">09.74.76.80.67</p>
      </div>
    </div>
  </section>
);


/**
 * Export
 */
export default CompanyAddress;

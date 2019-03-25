/**
 * NPM import
 */
import React from 'react';
import { Link } from 'react-router-dom';
import PropTypes from 'prop-types';

/**
 * Code
 */
const Nav = ({ isLogged }) => (
  <header>
    <nav className="navbar has-shadow is-primary is-fixed-top">
      <div className="navbar-brand">
        <Link to="/" className="navbar-item">
          <img src="src/cerberus_logo.png" alt="Logo o'Wine'rs" />
          <h1 className="title is-3 has-text-white">o'beer</h1>
        </Link>
        <div className="navbar-burger burger has-dropdown" data-target="navMenuExample4">
          <span />
          <span />
          <span />
        </div>
      </div>
      <div id="navMenuExample4" className="navbar-menu">
        <div className="navbar-start">
          <Link to="/" className="navbar-item is-size-4 is-active">
            Home
          </Link>
        </div>
        <div className="navbar-end">
          <div className="navbar-item">
            <div className="field is-grouped">
              {isLogged === false && (
                <>
                  <p className="control">
                    <Link to="/signup" id="sign-up" className="button is-transparent is-size-5">
                      <span>S'inscrire</span>
                    </Link>
                  </p>
                  <p className="control">
                    <Link to="/login" className="button is-transparent is-size-5">
                      <span>Se connecter</span>
                    </Link>
                  </p>
                </>
              )}
              {isLogged === true && (
                <p className="control">
                  <a id="sign-up" className="button is-transparent is-size-5">
                    <span>Se déconnecter</span>
                  </a>
                </p>
              )}
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>
);

Nav.propTypes = {
  isLogged: PropTypes.bool.isRequired,
};

/**
 * TODO :
 * - configurer le burger avec du JS pour faire apparaitre
 * les liens home s'inscrire et se connecter quand on clique dessus avec React
 */

/**
 * Export
 */
export default Nav;

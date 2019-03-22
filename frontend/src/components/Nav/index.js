/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Code
 */
const Nav = ({ isLogged }) => (
  <header>
    <nav className="navbar has-shadow is-primary is-fixed-top">
      <div className="navbar-brand">
        <a className="navbar-item" href="/">
          <img src="src/cerberus_logo.png" alt="Logo o'Wine'rs" />
          <h1 className="title is-3 has-text-white">o'Wine'rs</h1>
        </a>
        <div className="navbar-burger burger" data-target="navMenuExample4">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <div id="navMenuExample4" className="navbar-menu">
        <div className="navbar-start">
          <a className="navbar-item is-size-4 is-active" href="#">
            Home
          </a>
        </div>
        <div className="navbar-end">
          <div className="navbar-item">
            <div className="field is-grouped">
              {isLogged === false && (
                <>
                  <p className="control">
                    <a id="sign-up" className="button is-transparent is-size-5">
                      <span>S'inscrire</span>
                    </a>
                  </p>
                  <p className="control">
                    <a className="button is-transparent is-size-5">
                      <span>Se connecter</span>
                    </a>
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
 * - remettre le style flatly convenu au dapart qui a disparu à l'encapsulation React
 */

/**
 * Export
 */
export default Nav;

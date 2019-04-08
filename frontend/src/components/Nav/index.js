/**
 * NPM import
 */
import React from 'react';
import { Link } from 'react-router-dom';
import classNames from 'classnames';
import PropTypes from 'prop-types';
import {
  FaHome,
  FaUserCircle,
  FaAt,
  FaSignInAlt,
  FaSignature,
  FaSignOutAlt,
} from 'react-icons/fa';

/**
 * Code
 */
const Nav = ({
  isLogged,
  logOut,
  navbarIsActive,
  toggleNavBar,
}) => (
  <header>
    <nav className="navbar has-shadow is-primary is-fixed-top">
      <div className="navbar-brand">
        <Link to={(isLogged && '/catalog') || '/'} className="navbar-item">
          <img src="./cerberus_logo.png" alt="Logo Beer o'Clock" />
          <h1 className="title is-1 has-text-white has-text-weight-bold ">&nbsp;Beer o'Clock</h1>
        </Link>
        <div
          className={classNames(
            'navbar-burger',
            'burger',
            {
              'is-active': navbarIsActive,
            },
          )}
          data-target="navMenuExample4"
          onClick={toggleNavBar}
        >
          <span />
          <span />
          <span />
        </div>
      </div>
      <div
        id="navMenuExample4"
        className={classNames(
          'navbar-menu',
          {
            'is-active': navbarIsActive,
          },
        )}
      >
        <div className="navbar-start">
          {
          !isLogged && (
            <Link to="/" className="navbar-item is-size-4 is-active">
              <FaHome />&nbsp;Home
            </Link>
          )
          }
          {
          isLogged && (
            <>
              <Link to="/profile" className="navbar-item is-size-4 is-active">
                <FaUserCircle />&nbsp;Mon Profil
              </Link>
              <Link to="/contact" className="navbar-item is-size-4 is-active">
                <FaAt />&nbsp;Contacts
              </Link>
            </>
          )
          }
        </div>
        <div className="navbar-end">
          <div className="navbar-item">
            <div className="field is-grouped">
              {isLogged === false && (
                <>
                  <p className="control">
                    <Link to="/signup" id="sign-up" className="button is-transparent is-size-5">
                      <span><FaSignature />&nbsp;S'inscrire</span>
                    </Link>
                  </p>
                  <p className="control">
                    <Link to="/login" className="button is-transparent is-size-5">
                      <span><FaSignInAlt />&nbsp;Se connecter</span>
                    </Link>
                  </p>
                </>
              )}
              {isLogged === true && (
                <p className="control" onClick={logOut}>
                  <a id="sign-up" className="button is-transparent is-size-5">
                    <span><FaSignOutAlt />&nbsp;Se déconnecter</span>
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
  navbarIsActive: PropTypes.bool.isRequired,
  logOut: PropTypes.func.isRequired,
  toggleNavBar: PropTypes.func.isRequired,
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

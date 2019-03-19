/**
 * NPM import
 */
import React from 'react';

/**
 * Code
 */
const Nav = () => (
  <header>
    <nav className="navbar is-info">
      <div className="navbar-brand">
        <a className="navbar-item" href="/">
          <img src="https://cdn.dribbble.com/users/612987/screenshots/4309700/cerberus-logo.jpg" alt="Logo o'Wine'rs" width="28" height="20" />
        </a>
        <div className="navbar-burger burger" data-target="navMenuExample4">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <div id="navMenuExample4" className="navbar-menu">
        <div className="navbar-start">
          <a className="navbar-item is-size-4" href="#">
            Home
          </a>
        </div>
        <div className="navbar-end">
          <div className="navbar-item">
            <div className="field is-grouped">
              <p className="control">
                <a id="sign-up" className="button is-primary is-size-5">
                  <span>S'inscrire</span>
                </a>
              </p>
              <p className="control">
                <a className="button is-primary is-size-5">
                  <span>Se connecter</span>
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>
);
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

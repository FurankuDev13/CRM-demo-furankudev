/**
 * NPM import
 */
import React from 'react';

/**
 * Local import
 */
import './app.scss';

/**
 * Code
 */
const Nav = () => (
  <header>
    <nav class="navbar is-info">
      <div class="navbar-brand">
        <a class="navbar-item" href="/">
          <img src="https://cdn.dribbble.com/users/612987/screenshots/4309700/cerberus-logo.jpg" alt="Logo o'Wine'rs" width="28" height="20">
        </a>
        <a class="navbar-item is-hidden-desktop" href="https://github.com/jgthms/bulma" target="_blank">
          <span class="icon" style="color: #333;">
            <i class="fa fa-github"></i>
          </span>
        </a>
        <a class="navbar-item is-hidden-desktop" href="https://twitter.com/jgthms" target="_blank">
          <span class="icon" style="color: #55acee;">
            <i class="fa fa-twitter"></i>
          </span>
        </a>
        <div class="navbar-burger burger" data-target="navMenuExample4">
        <span></span>
        <span></span>
        <span></span>
        </div>
    </div>
    <div id="navMenuExample4" class="navbar-menu">
        <div class="navbar-start">
            <a class="navbar-item is-size-4" href="#">
            Home
            </a>
        </div>
        <div class="navbar-end">
            <div class="navbar-item">
            <div class="field is-grouped">
                <p class="control">
                <a id="sign-up" class="button is-primary is-size-5">
                <span>S'inscrire</span>
                </a>
                </p>
                <p class="control">
                <a class="button is-primary is-size-5" >
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
 * Export
 */
export default Nav;






<header>
<nav class="navbar is-info">
    <div class="navbar-brand">
    <a class="navbar-item" href="/">
    <img src="https://cdn.dribbble.com/users/612987/screenshots/4309700/cerberus-logo.jpg" alt="Logo o'Wine'rs" width="28" height="20">
    </a>
    <a class="navbar-item is-hidden-desktop" href="https://github.com/jgthms/bulma" target="_blank">
    <span class="icon" style="color: #333;">
        <i class="fa fa-github"></i>
    </span>
    </a>
    <a class="navbar-item is-hidden-desktop" href="https://twitter.com/jgthms" target="_blank">
    <span class="icon" style="color: #55acee;">
        <i class="fa fa-twitter"></i>
    </span>
    </a>
    <div class="navbar-burger burger" data-target="navMenuExample4">
    <span></span>
    <span></span>
    <span></span>
    </div>
</div>
<div id="navMenuExample4" class="navbar-menu">
    <div class="navbar-start">
        <a class="navbar-item is-size-4" href="#">
        Home
        </a>
    </div>
    <div class="navbar-end">
        <div class="navbar-item">
        <div class="field is-grouped">
            <p class="control">
            <a id="sign-up" class="button is-primary is-size-5">
            <span>S'inscrire</span>
            </a>
            </p>
            <p class="control">
            <a class="button is-primary is-size-5" >
                <span>Se connecter</span>
            </a>
            </p>
        </div>
        </div>
    </div>
    </div>
</nav>
</header>
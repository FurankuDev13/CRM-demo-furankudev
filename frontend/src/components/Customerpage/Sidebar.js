/**
 * NPM import
 */
import React from 'react';

/**
 * Local import
 */
import './sidebar.scss';

const Sidebar = () => (
  <aside className="menu column">
    <a className="control button is-light aside-button">
      <span>Poser une question</span>
    </a>
    <a className="control button is-light">
      <span>Faire une demande<br />de devis</span>
    </a>
    <a className="control button is-light">
      <span>Consulter nos<br />catégories</span>
    </a>
  </aside>
);

/**
 * Export
 */
export default Sidebar;

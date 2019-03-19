/**
 * NPM import
 */
import React from 'react';

/**
 * Local import
 */
import './sidebar.scss';

const Sidebar = () => (
  <aside className="menu column is-one-quarter">
    <a className="control button is-light is-size-5 aside-button">
      <span>Poser une question</span>
    </a>
    <a className="control button is-light is-size-5">
      <span>Faire une demande<br />de devis</span>
    </a>
    <a className="control button is-light is-size-5">
      <span>Consulter nos<br />catégories</span>
    </a>
  </aside>
);
/**
 * TODO :
 * - ajouter des alts appropriés lorsqu'on aura ajouté de vrais articles / catégories
 */

/**
 * Export
 */
export default Sidebar;

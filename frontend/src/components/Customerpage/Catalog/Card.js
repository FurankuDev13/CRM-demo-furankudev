/**
 * NPM import
 */
import React from 'react';

/**
 * Local import
 */

const Catalog = ({ name, listPrice, picture }) => (
  <div className="card">
    <div className="card-image">
      <figure className="image is-4by3">
        <img src={picture} alt="Placeholder" />
      </figure>
    </div>
    <div className="card-content">
      <div className="media">
        <div className="media-content">
          <p className="title is-4">{name}</p>
          <p className="subtitle is-4">Prix : {listPrice} €</p>
        </div>
        <div className="media-right button is-light">
          <span>+</span>
        </div>
      </div>
    </div>
  </div>
);
/**
 * TODO :
 * - ajouter des alts appropriés lorsqu'on aura ajouté de vrais articles / catégories
 */

/**
 * Export
 */
export default Catalog;

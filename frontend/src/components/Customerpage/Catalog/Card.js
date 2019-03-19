/**
 * NPM import
 */
import React from 'react';

/**
 * Local import
 */

const Catalog = () => (
  <div className="card">
    <div className="card-image">
      <figure className="image is-4by3">
        <img src="https://bulma.io/images/placeholders/1280x960.png" alt="Placeholder" />
      </figure>
    </div>
    <div className="card-content">
      <div className="media">
        <div className="media-content">
          <p className="title is-4">Mon article</p>
          <p className="subtitle is-4">Prix : 32 €</p>
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

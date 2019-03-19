/**
 * NPM import
 */
import React from 'react';

/**
 * Local import
 */
import Card from './Card';

import './catalog.scss';

const Catalog = () => (
  <div className="catalog">
    <Card />
    <Card />
    <Card />
    <Card />
    <Card />
    <Card />
    <Card />
    <Card />
    <Card />
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

/**
 * NPM import
 */
import React from 'react';

/**
 * Local import
 */
import Sidebar from './Sidebar';
import Catalog from './Catalog';

const CustomerPage = () => (
  <main className="columns is-gapless is-spaced">
    <Sidebar />
    <Catalog />
  </main>
);
/**
 * TODO :
 * - ajouter des alts appropriés lorsqu'on aura ajouté de vrais articles / catégories
 */

/**
 * Export
 */
export default CustomerPage;

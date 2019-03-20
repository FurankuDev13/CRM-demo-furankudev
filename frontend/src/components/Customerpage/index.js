/**
 * NPM import
 */
import React from 'react';

/**
 * Local import
 */
import Catalog from 'src/containers/Catalog';
import Sidebar from './Sidebar';

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

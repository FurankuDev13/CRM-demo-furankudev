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
 * Export
 */
export default CustomerPage;

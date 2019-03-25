/**
 * NPM import
 */
import React from 'react';

import { Route } from 'react-router-dom';

/**
 * Local import
 */
import Catalog from 'src/containers/Catalog';
import Categories from 'src/containers/Categories';
import Sidebar from 'src/components/Side';

const CustomerPage = () => (
  <main className="columns is-gapless is-spaced">
    <Sidebar />
    <Route
      path="/catalog"
      component={Catalog}
    />
    <Route
      path="/categories"
      component={Categories}
    />
  </main>
);

/**
 * Export
 */
export default CustomerPage;

/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

import { Route } from 'react-router-dom';

/**
 * Local import
 */
import Catalog from 'src/containers/Catalog';
import Categories from 'src/containers/Categories';
import Sidebar from 'src/containers/Sidebar';

class CustomerPage extends React.Component {
  componentDidMount() {
    const { fetchCatalog, fetchCategories } = this.props;
    fetchCatalog();
    fetchCategories();
  }

  render() {
    return (
      <main className="columns is-gapless is-spaced">
        <Sidebar />
        <Route
          path="/catalog"
          component={Catalog}
        />
        <Route
          path="/category/:slug"
          component={Catalog}
        />
        <Route
          path="/categories"
          component={Categories}
        />
      </main>
    );
  }
}

CustomerPage.propTypes = {
  fetchCategories: PropTypes.func.isRequired,
  fetchCatalog: PropTypes.func.isRequired,
};

/**
 * Export
 */
export default CustomerPage;

/* componentDidMount() {
    const { fetchCategories } = this.props;
    fetchCategories();
  } */

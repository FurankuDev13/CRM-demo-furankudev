/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Local import
 */
import Card from './Card';

import './catalog.scss';

class Catalog extends React.Component {
  componentDidMount() {
    const { fetchCatalog } = this.props;
    fetchCatalog();
  }

  render() {
    return (
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
  }
}

Catalog.propTypes = {
  fetchCatalog: PropTypes.func.isRequired,
};
/**
 * TODO :
 * - ajouter des alts appropriés lorsqu'on aura ajouté de vrais articles / catégories
 */

/**
 * Export
 */
export default Catalog;

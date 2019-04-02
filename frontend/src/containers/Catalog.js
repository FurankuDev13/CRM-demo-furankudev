/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Catalog from 'src/components/Customerpage/Catalog';
import { getCurrentCategory, getCategoryFromSlug } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = (state, ownProps) => {
  let currentList;
  let category = 'Catalogue complet';
  const { categoryList } = state;
  switch (ownProps.location.pathname) {
    case '/catalog': {
      currentList = state.catalogList;
      break;
    }
    default: {
      const { params } = ownProps.match;
      category = getCategoryFromSlug(categoryList, params.slug);
      currentList = getCurrentCategory(state.catalogList, params.slug);
      break;
    }
  }
  return {
    category,
    currentList,
  };
};

const mapDispatchToProps = () => ({});

const CatalogContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Catalog);


/**
 * Export
 */
export default CatalogContainer;

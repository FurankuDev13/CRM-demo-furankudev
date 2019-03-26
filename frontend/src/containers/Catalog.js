/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Catalog from 'src/components/Customerpage/Catalog';
import { getCurrentCategory } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = (state, ownProps) => {
  let currentList;
  switch (ownProps.location.pathname) {
    case '/catalog': {
      currentList = state.catalogList;
      break;
    }
    default: {
      const { params } = ownProps.match;
      currentList = getCurrentCategory(state.catalogList, params.slug);
      break;
    }
  }
  return {
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

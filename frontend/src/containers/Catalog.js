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
  const order = state.fields.articleOrder.articleSelect;
  switch (order) {
    case 'Par prix croissant': {
      currentList.sort((a, b) => a.listPrice - b.listPrice);
      break;
    }
    case 'Par prix décroissant': {
      currentList.sort((a, b) => b.listPrice - a.listPrice);
      break;
    }
    case 'Ordre alphabetique inverse': {
      currentList.sort((a, b) => {
        const nameA = a.name.toUpperCase();
        const nameB = b.name.toUpperCase();
        if (nameA > nameB) {
          return -1;
        }
        if (nameA < nameB) {
          return 1;
        }
        // names must be equal
        return 0;
      });

      break;
    }
    default: {
      currentList.sort((a, b) => {
        const nameA = a.name.toUpperCase();
        const nameB = b.name.toUpperCase();
        if (nameA < nameB) {
          return -1;
        }
        if (nameA > nameB) {
          return 1;
        }
        // names must be equal
        return 0;
      });
      break;
    }
  }

  const { articleOrder } = state.fields;
  return {
    category,
    currentList,
    articleOrder,
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

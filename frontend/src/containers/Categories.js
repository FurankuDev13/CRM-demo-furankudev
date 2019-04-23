/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Categories from 'src/components/Customerpage/Categories';

/**
 * Mapping
 */
const mapStateToProps = state => ({
  categoryList: state.categoryList,
});

const mapDispatchToProps = () => ({});

const CatalogContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Categories);

/**
 * Export
 */
export default CatalogContainer;

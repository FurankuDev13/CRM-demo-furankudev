/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Customepage from 'src/components/Customerpage';

// Action Creators
import { fetchCatalog, fetchCategories } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = () => ({});

const mapDispatchToProps = dispatch => ({
  fetchCatalog: () => {
    dispatch(fetchCatalog());
  },
  fetchCategories: () => {
    dispatch(fetchCategories());
  },
});

const CustomerpageContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Customepage);

/**
 * Export
 */
export default CustomerpageContainer;

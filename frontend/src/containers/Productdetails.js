/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Productdetails from 'src/components/Customerpage/Catalog/Productdetails';

/**
 * Mapping
 */
const mapStateToProps = (state) => {
  const { currentProduct } = state;
  return ({
    ...currentProduct,
  });
};

const mapDispatchToProps = () => ({});

const ProductdetailsContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Productdetails);

/**
 * Export
 */
export default ProductdetailsContainer;

/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Card from 'src/components/Customerpage/Catalog/Card';
import { displayItem } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = () => ({
});

const mapDispatchToProps = (dispatch, ownProps) => ({
  displayItem: () => {
    dispatch(displayItem(ownProps));
  },
});


const CardContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Card);

/**
 * Export
 */
export default CardContainer;

/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Sidebar from 'src/components/Sidebar';
import { openQuestionModal } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = () => ({
});

const mapDispatchToProps = dispatch => ({
  openQuestionModal: () => {
    dispatch(openQuestionModal());
  },
});

const SidebarContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Sidebar);

/**
 * Export
 */
export default SidebarContainer;

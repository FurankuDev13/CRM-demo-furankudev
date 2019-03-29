/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Sidebar from 'src/components/Sidebar';
import { toggleQuestionModal } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = () => ({
});

const mapDispatchToProps = dispatch => ({
  toggleQuestionModal: () => {
    dispatch(toggleQuestionModal());
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

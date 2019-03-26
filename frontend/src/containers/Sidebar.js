/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Sidebar from 'src/components/Sidebar';
import { toggleQuestionForm } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = () => ({
});

const mapDispatchToProps = dispatch => ({
  toggleQuestionForm: () => {
    dispatch(toggleQuestionForm());
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

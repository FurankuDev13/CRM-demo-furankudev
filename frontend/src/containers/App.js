/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import App from 'src/components/App';
import { closeQuestionModal } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = (state) => {
  const {
    view,
    logEmail,
    isLogged,
    questionModalIsActive,
  } = state;
  return ({
    isLogged,
    logEmail,
    view,
    questionModalIsActive,
  });
};

const mapDispatchToProps = dispatch => ({
  closeQuestionModal: () => {
    dispatch(closeQuestionModal());
  },
});


const AppContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(App);

/**
 * Export
 */
export default AppContainer;

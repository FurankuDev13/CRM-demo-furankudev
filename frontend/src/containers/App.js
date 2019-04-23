/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import App from 'src/components/App';
import { toggleQuestionModal } from 'src/store/reducer';

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
  toggleQuestionModal: () => {
    dispatch(toggleQuestionModal());
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

/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import App from 'src/components/App';

/**
 * Mapping
 */
const mapStateToProps = (state) => {
  const {
    view,
    logEmail,
    isLogged,
    askQuestionElementIsActive,
  } = state;
  return ({
    isLogged,
    logEmail,
    view,
    askQuestionElementIsActive,
  });
};

const mapDispatchToProps = () => ({});


const AppContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(App);

/**
 * Export
 */
export default AppContainer;

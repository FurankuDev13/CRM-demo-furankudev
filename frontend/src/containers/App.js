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
  } = state;
  return ({
    isLogged,
    logEmail,
    view,
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

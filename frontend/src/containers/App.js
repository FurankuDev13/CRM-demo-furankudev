/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import App from 'src/components/App';

// mapping
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

// Container
const AppContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(App);

/* 2 temps
const createContainer = connect(mapStateToProps, mapDispatchToProps);
const ExampleContainer = createContainer(Example);
*/

/**
 * Export
 */
export default AppContainer;

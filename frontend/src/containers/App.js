/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import App from 'src/components/App';

// Action Creators
// import { doSomething } from 'src/store/reducer';

// mapping
const mapStateToProps = state => ({
  isLogged: state.isLogged,
});

const mapDispatchToProps = (dispatch, ownProps) => ({
});

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

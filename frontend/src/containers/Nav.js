/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Nav from 'src/components/Nav';

// Action Creators
import { logOut, toggleNavBar } from 'src/store/reducer';

/* === State (données) ===
 * - mapStateToProps retroune un objet de props pour le composant de présentation
 * - mapStateToProps met à dispo 2 params
 *  - state : le state du store (getState)
 *  - ownProps : les props passées au container
 * Pas de data à transmettre ? const mapStateToProps = null;
 */
const mapStateToProps = (state) => {
  const { isLogged, logEmail, navbarIsActive } = state;
  return ({
    isLogged,
    logEmail,
    navbarIsActive,
  });
};

/* === Actions ===
 * - mapDispatchToProps retroune un objet de props pour le composant de présentation
 * - mapDispatchToProps met à dispo 2 params
 *  - dispatch : la fonction du store pour dispatcher une action
 *  - ownProps : les props passées au container
 * Pas de disptach à transmettre ? const mapDispatchToProps = {};
 */
const mapDispatchToProps = dispatch => ({
  logOut: () => {
    localStorage.removeItem('email');
    dispatch(logOut());
  },
  toggleNavBar: () => {
    dispatch(toggleNavBar());
  },
});

// Container
const CatalogContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Nav);

/* 2 temps
const createContainer = connect(mapStateToProps, mapDispatchToProps);
const ExampleContainer = createContainer(Example);
*/

/**
 * Export
 */
export default CatalogContainer;

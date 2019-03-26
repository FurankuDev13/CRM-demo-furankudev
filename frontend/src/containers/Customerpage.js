/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Customepage from 'src/components/Customerpage';

// Action Creators
import { fetchCatalog, fetchCategories } from 'src/store/reducer';

/* === State (données) ===
 * - mapStateToProps retroune un objet de props pour le composant de présentation
 * - mapStateToProps met à dispo 2 params
 *  - state : le state du store (getState)
 *  - ownProps : les props passées au container
 * Pas de data à transmettre ? const mapStateToProps = null;
 */
const mapStateToProps = () => ({});

/* === Actions ===
 * - mapDispatchToProps retroune un objet de props pour le composant de présentation
 * - mapDispatchToProps met à dispo 2 params
 *  - dispatch : la fonction du store pour dispatcher une action
 *  - ownProps : les props passées au container
 * Pas de disptach à transmettre ? const mapDispatchToProps = {};
 */
const mapDispatchToProps = dispatch => ({
  fetchCatalog: () => {
    dispatch(fetchCatalog());
  },
  fetchCategories: () => {
    dispatch(fetchCategories());
  },
});

// Container
const CustomerpageContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Customepage);

/* 2 temps
const createContainer = connect(mapStateToProps, mapDispatchToProps);
const ExampleContainer = createContainer(Example);
*/

/**
 * Export
 */
export default CustomerpageContainer;

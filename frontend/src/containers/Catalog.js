/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Catalog from 'src/components/Customerpage/Catalog';

// Action Creators
import { fetchCatalog } from 'src/store/reducer';

/* === State (données) ===
 * - mapStateToProps retroune un objet de props pour le composant de présentation
 * - mapStateToProps met à dispo 2 params
 *  - state : le state du store (getState)
 *  - ownProps : les props passées au container
 * Pas de data à transmettre ? const mapStateToProps = null;
 */
const mapStateToProps = state => ({
  catalogList: state.catalogList,
});

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
});

// Container
const CatalogContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Catalog);

/* 2 temps
const createContainer = connect(mapStateToProps, mapDispatchToProps);
const ExampleContainer = createContainer(Example);
*/

/**
 * Export
 */
export default CatalogContainer;

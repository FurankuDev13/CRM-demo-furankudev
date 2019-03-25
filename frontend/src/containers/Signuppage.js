/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Signuppage from 'src/components/Signuppage';

// Action Creators

/* === State (données) ===
 * - mapStateToProps retroune un objet de props pour le composant de présentation
 * - mapStateToProps met à dispo 2 params
 *  - state : le state du store (getState)
 *  - ownProps : les props passées au container
 * Pas de data à transmettre ? const mapStateToProps = null;
 */
const mapStateToProps = state => ({
  signupFields: state.fields.signup,
});

/* === Actions ===
 * - mapDispatchToProps retroune un objet de props pour le composant de présentation
 * - mapDispatchToProps met à dispo 2 params
 *  - dispatch : la fonction du store pour dispatcher une action
 *  - ownProps : les props passées au container
 * Pas de disptach à transmettre ? const mapDispatchToProps = {};
 */
const mapDispatchToProps = () => ({});

// Container
const SignuppageContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Signuppage);

/**
 * Export
 */
export default SignuppageContainer;

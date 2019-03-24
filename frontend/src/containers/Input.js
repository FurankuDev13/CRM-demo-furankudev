/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Input from 'src/components/Form/Input';

// Action Creators
import { inputChange } from 'src/store/reducer';

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
const mapDispatchToProps = (dispatch, ownProps) => {
  const { formOrigin, name } = ownProps;
  return ({
    inputChange: (fieldInfos) => {
      dispatch(inputChange(fieldInfos, formOrigin, name));
    },
  });
};

// Container
const InputContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Input);

/* 2 temps
const createContainer = connect(mapStateToProps, mapDispatchToProps);
const ExampleContainer = createContainer(Example);
*/

/**
 * Export
 */
export default InputContainer;

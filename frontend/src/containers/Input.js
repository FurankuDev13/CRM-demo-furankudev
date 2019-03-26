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

/**
 * Mapping
 */
const mapStateToProps = () => ({});

const mapDispatchToProps = (dispatch, ownProps) => {
  const { formOrigin, name } = ownProps;
  return ({
    inputChange: (fieldInfos) => {
      dispatch(inputChange(fieldInfos, formOrigin, name));
    },
  });
};

const InputContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Input);

/**
 * Export
 */
export default InputContainer;

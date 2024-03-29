/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Select from 'src/components/Form/Select';

// Action Creators
import { inputChange } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = (state, ownProps) => {
  const { formOrigin, name } = ownProps;
  return ({
    value: state.fields[formOrigin][name],
  });
};

const mapDispatchToProps = (dispatch, ownProps) => {
  const { formOrigin, name } = ownProps;
  return ({
    inputChange: (fieldInfos) => {
      dispatch(inputChange(fieldInfos, formOrigin, name));
    },
  });
};

const SelectContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Select);

/**
 * Export
 */
export default SelectContainer;

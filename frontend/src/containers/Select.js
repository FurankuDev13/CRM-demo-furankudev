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
const mapStateToProps = state => ({
  value: state.fields.question.questionSelect,
});

const mapDispatchToProps = (dispatch, ownProps) => {
  const { formOrigin, name } = ownProps;
  return ({
    inputChange: (fieldInfos) => {
      console.log(fieldInfos, formOrigin, name);
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

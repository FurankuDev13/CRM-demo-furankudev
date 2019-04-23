/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import QuestionForm from 'src/components/QuestionForm';
import { toggleQuestionModal } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = state => ({
  questionFields: state.fields.question,
  formErrors: state.formErrors,
});

const mapDispatchToProps = dispatch => ({
  toggleQuestionModal: () => {
    dispatch(toggleQuestionModal());
  },
});

const QuestionFormContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(QuestionForm);

/**
 * Export
 */
export default QuestionFormContainer;

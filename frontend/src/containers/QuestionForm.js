/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import QuestionForm from 'src/components/QuestionForm';
import { toggleQuestionForm } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = state => ({
  questionFields: state.fields.question,
});

const mapDispatchToProps = dispatch => ({
  toggleQuestionForm: () => {
    dispatch(toggleQuestionForm());
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

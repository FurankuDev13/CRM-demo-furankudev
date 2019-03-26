/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import QuestionForm from 'src/components/QuestionForm';

/**
 * Mapping
 */
const mapStateToProps = state => ({
  questionFields: state.fields.question,
});

const mapDispatchToProps = () => ({});

const QuestionFormContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(QuestionForm);

/**
 * Export
 */
export default QuestionFormContainer;

/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * local import
 */
import Form from 'src/containers/Form';
import './QuestionForm.scss';

/**
 * Code
 */

const QuestionForm = ({ questionFields, toggleQuestionForm }) => {
  const { title, content } = questionFields;
  const tabl = [
    {
      name: 'title',
      label: 'Titre',
      type: 'text',
      value: title,
      placeholder: '',
    },
    {
      name: 'content',
      label: 'Question',
      element: 'textarea',
      type: 'text',
      value: content,
      placeholder: '',
    },
  ];
  return (
    <Form
      tabl={tabl}
      formOrigin="question"
    >
      <button
        id="question-toggle"
        type="button"
        className="button is-danger"
        onClick={toggleQuestionForm}
      >
        <p>+</p>
      </button>
    </Form>
  );
};

QuestionForm.propTypes = {
  questionFields: PropTypes.shape({
    title: PropTypes.string.isRequired,
    content: PropTypes.string.isRequired,
  }).isRequired,
  toggleQuestionForm: PropTypes.func.isRequired,
};

/**
 * Export
 */
export default QuestionForm;

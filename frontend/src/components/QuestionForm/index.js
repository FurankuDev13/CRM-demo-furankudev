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

const QuestionForm = ({ questionFields, toggleQuestionModal }) => {
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
    <div className="modal-content">
      <Form
        tabl={tabl}
        formOrigin="question"
      >
        <button
          id="question-toggle"
          type="button"
          className="button is-danger modal-close"
          onClick={toggleQuestionModal}
        />
      </Form>
    </div>
  );
};

QuestionForm.propTypes = {
  questionFields: PropTypes.shape({
    title: PropTypes.string.isRequired,
    content: PropTypes.string.isRequired,
  }).isRequired,
  toggleQuestionModal: PropTypes.func.isRequired,
};

/**
 * Export
 */
export default QuestionForm;

/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * local import
 */
import Form from 'src/containers/Form';
import { deleteNotification } from 'src/store/reducer';
import './QuestionForm.scss';

/**
 * Code
 */

const QuestionForm = ({ questionFields, toggleQuestionModal, formErrors }) => {
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
        <div id="notification" className="notification is-danger is-hidden">
          <button
            type="button"
            className="delete"
            onClick={deleteNotification}
          />
          <ul>
            {formErrors.map(error => (
              <li key={error}>
                <p>
                  {error}
                </p>
              </li>
            ))}
          </ul>
        </div>
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
  formErrors: PropTypes.arrayOf(
    PropTypes.string.isRequired,
  ).isRequired,
};

/**
 * Export
 */
export default QuestionForm;

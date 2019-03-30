/**
 * NPM import
 */
import React from 'react';
import { Link } from 'react-router-dom';
import PropTypes from 'prop-types';

/**
 * Local import
 */
import './sidebar.scss';

const Sidebar = ({ toggleQuestionModal }) => (
  <aside className="menu column">
    <a className="control button is-light aside-button" onClick={toggleQuestionModal}>
      <span>Poser une question</span>
    </a>
    <a className="control button is-light">
      <span>Faire une demande<br />de devis</span>
    </a>
    <Link to="/categories" className="control button is-light">
      <span>Consulter nos<br />catégories</span>
    </Link>
  </aside>
);

Sidebar.propTypes = {
  toggleQuestionModal: PropTypes.func.isRequired,
};

/**
 * Export
 */
export default Sidebar;

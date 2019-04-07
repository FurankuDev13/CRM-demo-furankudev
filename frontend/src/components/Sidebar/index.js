/**
 * NPM import
 */
import React from 'react';
import { Link } from 'react-router-dom';
import PropTypes from 'prop-types';
import {
  FaQuestionCircle,
  FaBeer,
} from 'react-icons/fa';

/**
 * Local import
 */
import './sidebar.scss';

const Sidebar = ({ toggleQuestionModal }) => (
  <aside className="menu column">
    <a className="control button is-light aside-button" onClick={toggleQuestionModal}>
      <span className="has-text-weight-bold"><FaQuestionCircle />&nbsp;  Poser <br />une question</span>
    </a>
    <Link to="/categories" className="control button is-light">
      <span className="has-text-weight-bold"><FaBeer />&nbsp;  Consulter <br />nos catégories</span>
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

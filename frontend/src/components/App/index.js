/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';

/**
 * Local import
 */
import { Route, Redirect, Switch } from 'react-router-dom';
import Nav from 'src/containers/Nav';
import Homepage from 'src/containers/Homepage';
import Customerpage from 'src/containers/Customerpage';
import Contactpage from 'src/containers/Contactpage';
import Loginpage from 'src/containers/Loginpage';
import Signuppage from 'src/containers/Signuppage';
import Footer from 'src/components/Footer';
import NotFound from 'src/components/NotFound';
import QuestionForm from 'src/containers/QuestionForm';
import Profilepage from 'src/containers/Profilepage';
import './App.scss';
/**
 * Code
 */
const App = ({ isLogged, toggleQuestionModal, questionModalIsActive }) => (
  <div>
    <Nav />
    <div id="popmessage" />
    <Switch>
      <Route
        exact
        path="/"
        render={() => (
          !isLogged ? (
            <Homepage />
          ) : (
            <Redirect to="/catalog" />
          )
        )}
      />
      <Route
        path="/catalog"
        render={() => (
          isLogged ? (
            <Customerpage />
          ) : (
            <Redirect to="/login" />
          )
        )}
      />
      <Route
        path="/profile"
        render={() => (
          isLogged ? (
            <Profilepage />
          ) : (
            <Redirect to="/login" />
          )
        )}
      />
      <Route
        path="/contact"
        render={() => (
          isLogged ? (
            <Contactpage />
          ) : (
            <Redirect to="/login" />
          )
        )}
      />
      <Route
        path="/categories"
        render={() => (
          isLogged ? (
            <Customerpage />
          ) : (
            <Redirect to="/login" />
          )
        )}
      />
      <Route
        path="/category/:slug"
        render={() => (
          isLogged ? (
            <Customerpage />
          ) : (
            <Redirect to="/login" />
          )
        )}
      />
      <Route
        path="/login"
        render={() => (
          !isLogged ? (
            <Loginpage />
          ) : (
            <Redirect to="/catalog" />
          )
        )}
      />
      <Route
        path="/signup"
        render={() => (
          !isLogged ? (
            <Signuppage />
          ) : (
            <Redirect to="/catalog" />
          )
        )}
      />
      {/* fallback */}
      <Route component={NotFound} />
    </Switch>
    <Footer />
    {isLogged && (
      <div className={classNames(
        'modal',
        { 'is-active': questionModalIsActive },
      )}
      >
        <div className="modal-background" onClick={toggleQuestionModal} />
        <QuestionForm />
      </div>
    )}
  </div>
);

App.propTypes = {
  isLogged: PropTypes.bool.isRequired,
  questionModalIsActive: PropTypes.bool.isRequired,
  toggleQuestionModal: PropTypes.func.isRequired,
};

/**
 * Export
 */
export default App;

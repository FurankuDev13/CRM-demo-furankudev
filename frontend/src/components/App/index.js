/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Local import
 */
import { Route, Redirect, Switch } from 'react-router-dom';
import Nav from 'src/containers/Nav';
import Homepage from 'src/components/Homepage';
import Customerpage from 'src/containers/Customerpage';
import Loginpage from 'src/containers/Loginpage';
import Signuppage from 'src/containers/Signuppage';
import Footer from 'src/components/Footer';
import NotFound from 'src/components/NotFound';
import QuestionForm from 'src/containers/QuestionForm';


/**
 * Code
 */
const App = ({ isLogged, askQuestionElementIsActive }) => (
  <div>
    <Nav />
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
    {askQuestionElementIsActive && (
      <QuestionForm />
    )}
  </div>
);

App.propTypes = {
  isLogged: PropTypes.bool.isRequired,
  askQuestionElementIsActive: PropTypes.bool.isRequired,
};

/**
 * Export
 */
export default App;

/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Local import
 */
import { Route, Redirect, Switch } from 'react-router-dom';
import Nav from 'src/components/Nav';
import Homepage from 'src/components/Homepage';
import Customerpage from 'src/components/Customerpage';
import Loginpage from 'src/containers/Loginpage';
import Signuppage from 'src/containers/Signuppage';
import Footer from 'src/components/Footer';
import NotFound from 'src/components/NotFound';


/**
 * Code
 */
const App = ({ isLogged }) => (
  <div>
    <Nav
      isLogged={isLogged}
    />
    <Switch>
      <Route exact path="/" component={Homepage} />
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
        path="/login"
        render={() => (
          !isLogged ? (
            <Loginpage
              formOrigin="login"
            />
          ) : (
            <Redirect to="/catalog" />
          )
        )}
      />
      <Route
        path="/signup"
        render={() => (
          !isLogged ? (
            <Signuppage
              formOrigin="signup"
            />
          ) : (
            <Redirect to="/catalog" />
          )
        )}
      />
      {/* fallback */}
      <Route component={NotFound} />
    </Switch>
    <Footer />
  </div>
);

App.propTypes = {
  isLogged: PropTypes.bool.isRequired,
};

/**
 * Export
 */
export default App;

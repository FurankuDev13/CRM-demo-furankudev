/**
 * NPM import
 */
import React from 'react';

/**
 * Local import
 */
import { Route, Redirect, Switch } from 'react-router-dom';
import Nav from 'src/components/Nav';
import Homepage from 'src/components/Homepage';
import Customerpage from 'src/components/Customerpage';
import Loginpage from 'src/components/Loginpage';
import Signuppage from 'src/components/Signuppage';
import Footer from 'src/components/Footer';
import NotFound from 'src/components/NotFound';


/**
 * Code
 */
const App = ({ isLogged }) => (
  <div>
    <Nav 
      isLogged
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
  </div>
);
/**
 * TODO : quand la page de connexion sera faite, rediriger depuis la page catalogue
 * vers la page de connexion et non la page d'accueil
 */

/**
 * Export
 */
export default App;

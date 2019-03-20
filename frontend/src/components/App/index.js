/**
 * NPM import
 */
import React from 'react';

/**
 * Local import
 */
import { Route, Redirect } from 'react-router-dom';
import Nav from 'src/components/Nav';
import Homepage from 'src/components/Homepage';
import Customerpage from 'src/components/Customerpage';
import Footer from 'src/components/Footer';

/**
 * Code
 */
const App = isLogged => (
  <div>
    <Nav />
    <Route exact path="/" component={Homepage} />
    <Route
      path="/catalog"
      render={() => (
        isLogged ? (
          <Customerpage />
        ) : (
          <Redirect to="/" />
        ))}
    />
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

/**
 * NPM import
 */
import React from 'react';

/**
 * Local import
 */
import { Route } from 'react-router-dom';
import Nav from 'src/components/Nav';
import Homepage from 'src/components/Homepage';
import Customerpage from 'src/components/Customerpage';
import Footer from 'src/components/Footer';

/**
 * Code
 */
const App = () => (
  <div>
    <Nav />
    <Route exact path="/" component={Homepage} />
    <Route path="/catalog" component={Customerpage} />
    <Footer />
  </div>
);

/**
 * Export
 */
export default App;

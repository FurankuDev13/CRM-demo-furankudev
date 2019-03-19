/**
 * NPM import
 */
import React from 'react';

/**
 * Local import
 */
import './app.scss';
import Nav from 'src/components/Nav';
import Homepage from 'src/components/Homepage';
import Footer from 'src/components/Footer';

/**
 * Code
 */
const App = () => (
  <div className="container">
    <Nav />
    <Homepage />
    <Footer />
  </div>
);

/**
 * Export
 */
export default App;

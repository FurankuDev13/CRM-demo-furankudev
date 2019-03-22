/**
 * Npm import
 */
import { createStore, compose, applyMiddleware } from 'redux';

/**
 * Local import
 */
import reducer from 'src/store/reducer';

import catalogMiddleware from './middlewares/Catalog';
import adminMiddleware from './middlewares/Admin';

// composition middleware
// eslint-disable-next-line no-underscore-dangle
const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;

const enhancers = composeEnhancers(
  applyMiddleware(catalogMiddleware),
  applyMiddleware(adminMiddleware),
);

/**
 * Store
 */
/* eslint-disable no-underscore-dangle */
const store = createStore(
  reducer,
  enhancers,
);
/* eslint-enable */

/**
 * Export
 */
export default store;

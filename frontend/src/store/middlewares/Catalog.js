/**
 * npm import
*/
import axios from 'axios';

/**
 * local import
*/
import { FETCH_CATALOG } from 'src/store/reducer';

// Middleware : ajax : gestion des lettres
const ajaxCatalog = store => next => (action) => {
  switch (action.type) {
    case FETCH_CATALOG:
      console.log('Je cherche ! je cherche !');
      break;

    default:
      break;
  }
  next(action);
};

export default ajaxCatalog;

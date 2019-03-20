/**
 * npm import
*/
import axios from 'axios';

/* base URL à définir quand le back sera prêt */

/**
 * local import
*/
import { FETCH_CATALOG } from 'src/store/reducer';


const axiosUp = axios.create({
  baseURL: 'http://localhost:8001',
});

// Middleware : ajax : gestion des lettres
const ajaxCatalog = store => next => (action) => {
  switch (action.type) {
    case FETCH_CATALOG:
      console.log('Je cherche ! je cherche !');
      axiosUp.get('/api/products', {
        headers: {
          'Access-Control-Allow-Origin': '*',
          'Access-Control-Allow-Headers': '*',
        },
      })
        .then((response) => {
          console.log(response);
          /* store.dispatch(fetchSuccess(data) */
        })
        .catch((error) => {
          console.log(error);
        });
      break;

    default:
      break;
  }
  next(action);
};

export default ajaxCatalog;

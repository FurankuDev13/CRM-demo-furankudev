/**
 * npm import
*/
import axios from 'axios';

/**
 * local import
*/
import { FETCH_CATALOG, FETCH_CATEGORIES, fetchSuccess } from 'src/store/reducer';

/* TODO : redéfinir l'URL du backend en mode production juste avant la fin */

const axiosUp = axios.create({
  baseURL: 'http://127.0.0.1:8001',
});

// Middleware : ajax : gestion des lettres
const ajaxCatalog = store => next => (action) => {
  switch (action.type) {
    case FETCH_CATALOG:
      axiosUp.get('/api/contact/3/products')
        .then((response) => {
          const { data } = response;
          store.dispatch(fetchSuccess(data, 'catalogList'));
        })
        .catch((error) => {
          console.log(error);
        });
      break;

    case FETCH_CATEGORIES:
      axiosUp.get('/api/categories')
        .then((response) => {
          const { data } = response;
          store.dispatch(fetchSuccess(data, 'categoryList'));
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

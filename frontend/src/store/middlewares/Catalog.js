/**
 * npm import
*/
import axios from 'axios';

/**
 * local import
*/
import {
  FETCH_CATALOG,
  FETCH_CATEGORIES,
  FETCH_HOME_PAGE,
  fetchSuccess,
} from 'src/store/reducer';

/* TODO : redéfinir l'URL du backend en mode production juste avant la fin */

const axiosUp = axios.create({
  baseURL: 'http://localhost/Apotheose/crm/backend/public',
});
//http://cerberus-crm.space/backend/public

// Middleware : ajax : gestion des lettres
const ajaxCatalog = store => next => (action) => {
  switch (action.type) {
    case FETCH_CATALOG: {
      const { logId } = store.getState();
      axiosUp.get(`/api/contact/${logId}/products`)
        .then((response) => {
          const { data } = response;
          store.dispatch(fetchSuccess(data, 'catalogList'));
        })
        .catch((error) => {
          console.log(error);
        });
      break;
    }

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

    case FETCH_HOME_PAGE:
      axiosUp.get('/api/products?isOnHomePage=1')
        .then((response) => {
          const { data } = response;
          store.dispatch(fetchSuccess(data, 'articlesOnHomePage'));
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

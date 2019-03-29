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
  baseURL: 'http://127.0.0.1:8001/',
  headers: {
    Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1NTM4NzIxODQsInJvbGVzIjpbIlJPTEVfQVBJVVNFUiJdLCJ1c2VybmFtZSI6ImNlcmJlcnVzLmNybS5tYWlsZXJAZ21haWwuY29tIn0.PJ9ZVb_3ivHOHm6qRh6mhLZcwAu_AT74YoizZ0kItlZg-WaSMsNQPYQ8o-rDL_E1A6beQqtnEaI3w7u98WBl5Nk1RmEfYjVO_5J-P7LSjrJJ-IsabdGoe39jUcdfU2jYcVjdp0dQnc_gDXD83RGvU9brrEBfeH7vuUtGomUP2pRpgumZDWndzSMRWxrkaye8Wc6XeYnt-qNzzpCDLhHBjWb27x-WIqwYqpbqzjZT_wWlaWe8cfuAOP1DoSWGrb8kW9hsxbXuInSOdsK3oCrX45277eySO7OgmxuI4kAAf68JBQZfZtM2_7CAkmFBld_M2rnlBkiF7vCCMyU-Zu7xUWxguWu2cfDgRpMGAmEP1exAUQ20pYI5BZtQADXRtQdkq6_3HwmhirGYrr325IYpRcPrrhTI1_fAetx-U2wzoeMPHaq5dOn-T8K06h6ZOOWwfSsH-YrJa1ZcHlg0dX5zjj360L_gngPiTiW5T0JdWDkrnp0OAA53n_XFTzTedqVn0P6BXNS5iucB-odh72SEzov-fVHIJO5JocLCXPvjFDncevOfNyDEYc-9l_QwfO6ogjoDRQ6vHr_Y4mDxiQhAmFb5kG8K6hd4Ps_UabJs-Sf26lUK2aWAqrGagUH46pDT0zRh0XV7yE3j_SodI8KPb_BIvEd36vxK5fHJkqRoJzU',
  },
});
// http://cerberus-crm.space/backend/public

// Middleware : ajax : gestion des lettres
const ajaxCatalog = store => next => (action) => {
  switch (action.type) {
    case FETCH_CATALOG: {
      const { id } = store.getState().profile;
      axiosUp.get(`/api/contact/${id}/product`)
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
      axiosUp.get('/api/category')
        .then((response) => {
          const { data } = response;
          store.dispatch(fetchSuccess(data, 'categoryList'));
        })
        .catch((error) => {
          console.log(error);
        });
      break;

    case FETCH_HOME_PAGE:
      axiosUp.get('/api/product?isOnHomePage=1')
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

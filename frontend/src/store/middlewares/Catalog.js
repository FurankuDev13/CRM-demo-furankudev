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
    Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1NTQxOTE1MjQsInJvbGVzIjpbIlJPTEVfQVBJVVNFUiJdLCJ1c2VybmFtZSI6ImNlcmJlcnVzLmNybS5tYWlsZXJAZ21haWwuY29tIn0.JKliny4Ivf4qXrlRsv6XtxjsnOEZeqfHMXO6dJoNXuZNwMXCLYDIjBHbbsfKGWBNhrVpQ2MzA1jh0p_KBkRi1qCpnEZgilNqmZ3o6f5ww6ndhr3pvAUsy6rCgSY-7Hgv59BzaOrCznPaUvD-5675GH4wZaJ6zp8zBs0t9QTLsvC736muGuzwqJbePXRbefcejMUAPlKsbVMQzxZijW9xC4FrTBavAdOXPVEgIrYI2t1jenmRTaAZGQLIvKPXz8f9-hXW0Zaj1wKL5RlDvuQtI1VDombYgHoEBLOg7OXin1sGYJZ7uYddN2jOtxmyQhXiHUgUKrVCeL63qyhUDSNADqKlsP5VLgO9lQX4IL9M7OztpaJ2OaQ4UfDu_i370sJ6hqTYoKJ9HFFr9SySgcFDEwqEiyuQr4G3Sxh7vt_-gjnMeVLYxihMksuVB86FVCRmaWWCXm3I-WDVcauBssic7R2O_g5U-dRQb6uxw3lg_DoxCsZ3c76NqrMdLnUZT9FiElUu9t81Yn1T0x3xXvUX3738SvhY8XSOMvXbsvKv8vvh54WISEZBLxZVvxGg5NrldtUqKiBPq00-ArFbmIM1TjOYS0-qggDfhVv-6uHED9EqYzkLes99nLpMwDk6cSTLrzfGCfiybUlzUmjt3H1kqiZdKWPcaJ-sHSCub1iBB6Y',
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

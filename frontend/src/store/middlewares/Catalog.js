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
    Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1NTM4ODEyNDQsInJvbGVzIjpbIlJPTEVfQVBJVVNFUiJdLCJ1c2VybmFtZSI6ImNlcmJlcnVzLmNybS5tYWlsZXJAZ21haWwuY29tIn0.UxURpuzhDIQg7FijJyS3L387U0eHfeQ0DuYJl2m5vl8FGXrWe160FegbRElhygBB_iXUGUpoYM1t1HvDmMp6vbUU2XxUD-GivCI6Wick0kojqC_m2hXskm-oGCG6XB5BJLC3w4C7oN9bLUsktiiZbQ_DdHAammZ4KixIDbFsb2v_5N3saYy_NRxYG-XKTULL__EQ73OiVIbtGnJZMWIEb-RymcFBDwvn7V3ZlNLQ0DR-jYHHGJv0oGKHOB6jvs2JfwHnAMB6z9CjPrmLKSMlF9bWTmlMvmEmEhG1FxsnphQn8FPXmHn93shFQ8tAdsS479QcN1WeSZYAi38fF7iLyPUi-LcnBmPrZFZZY-j2x82ATHVtSgOu52h7jbPIw-g0Owu5IeHlzi32-vb6BsvMPSC1T1k5cquPUeyab5t6uLf0sVgFQE2DtmSI-dSOCju3-m_oqrTzxsAw3UirlItp8RnkEYbrds2iEa9kEWxwbRasTmGaVxmjaSpgASWq8Bp-YCIMEIQrvBd4jOqFcJWjqrYuA1xv_g73a6zV0edlacz_Vg4Zlb5xq6pRucpQSSLLvxchMRcElY7rNNNjf3l4q7uNB8u1At-lCjlmL4JeN6H8su61k8a4UuzoR1p2yhFg7q3uJuGWoctVHX6B31j33voiPlufBF2ZknEJbjbbNaI',
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

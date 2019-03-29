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
    Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1NTM4NTg2NTQsImV4cCI6MTU1NDIxODY1NCwicm9sZXMiOlsiUk9MRV9BUElVU0VSIl0sInVzZXJuYW1lIjoiY2VyYmVydXMuY3JtLm1haWxlckBnbWFpbC5jb20ifQ.XE6jXmzFvBuIhQfz8JpUFT4yritK2kVl4qGED5ui4r5l3XsVMRbPUrkADQS8ZolKFu-iwwP5RvzOQ-C4BxkJUV4Jjg4NYfiEY6u9uU3FS4Bvwxe5vkmp3MiGPcnNxtpMKH8tOmiJvWg3VmA699HgxqoEOFTPQuQFYPLm-m0voKVcbw7GAdPDB_faCOfLQ6U8X_2FgVoNgwCtWouHCm9UBg01E2kJ5uF3SXfG_WGvYOSCucw5IVEH3CTZW2lozDPtZbbJUiAWCLr9cVP6m31g8MjLjyW8bNRFmg6bnK1zf8EZJOP0Zv0kiVrvmli3V_O-aU7s_fa5DiAJ9ZpYkf4zNDNv8jgTtQPWorBeCGJvTRKAVSZqUd7I-IOqSbQlTI410QTwpNVIQD38Y8rB24W94URS-Dws8LOiHygL54qmNDLGDzNgDBlKK2AV35gNbLz0fHPYs3TYDpJ1KeuvN_vyP8dpIkDLGyypk6J_qXrOVjCMwvhXEyGxO9rDXIoe9ePRCIfyhlkuHQrSQvVzdZByYbSkH02OjJwi4NwieEO8Xu54BUgrmCqYWlz_ex8-o1S35S4_LXm91TZfmJFmUdfntA6emvPcUPt6mIe9Vjqu4Sv66igiqLmaBD_rD_WyaFa3khkBzErDBqd-RW_jRegkO4x-bxAViajYBQyWRNWHNfY',
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

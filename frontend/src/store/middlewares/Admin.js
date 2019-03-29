/**
 * npm import
*/
import axios from 'axios';

/**
 * local import
*/
import {
  SEND_LOGIN_REQUEST,
  SEND_REGISTER_REQUEST,
  SEND_QUESTION,
  sendLoginRequest,
  setProfile,
} from 'src/store/reducer';

/* TODO : redéfinir l'URL du backend en mode production juste avant la fin */

const axiosUp = axios.create({
  baseURL: 'http://127.0.0.1:8001/',
  headers: {
    Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1NTM4NTQ5NjIsImV4cCI6MTU1Mzg1ODU2Miwicm9sZXMiOlsiUk9MRV9BUElVU0VSIl0sInVzZXJuYW1lIjoiY2VyYmVydXMuY3JtLm1haWxlckBnbWFpbC5jb20ifQ.RkzqIFJKXCM-8SGbb9M-NSbTIOsnvS0eYwbZlg_Wm4HdjpG9zCG9WDQ5s0e5ooGF1KtFiSz5pVozx3UqQVpmeOjWHDKjGCM7K2eC3CxtnRoGP7_5LeFAyMZ8eSsK7qjM0U04n0LHLye-1LiHIU7ZMTHgqQlxEHESJtMlYm1ukkK7pTxbZP3l3awTasovkUALsTiY4nsz36m8-FsOgQXzBjBzcm00ZXTftQNMcoS9HHeU4AjT5VJ9T6NckDfQ5HiihkvVLfF9aUcfAxBmC1sEVoiGoWkRFGJ57ggUNZG1d8XQYIe-MOaZBvJWxonnVC8uATWuUuMkoJQFAXFP8vphuRmupAXZUHCAzxNHcYaUXAVijqEN9Lz-jAZQCFTkkYrrf_rJxTTkmx5Wm2isEo7uEit_zYKIKWipOj88Dt7yZUJnQnfRdUjbG9K23gUi8b_DW-c0rEmNd8fp6XZif5azxQRMzXQviAmpYFNFAjAuttEgVcWomHtgotjoVOoGb2dl1oJ9DgJiFGAQhUCBzZa3E4Bwd_ppDu1QzhaDIFg-M9k_VGtST4PYjvIqiW0O-kggs-ti3VgMDKvKwgxNWsZqEIE8gvWF2Oil7o_gDGkwBbuJ34juRDKOPfH87uoY-Z9XXitmyzty6zUzMHHigUiVS8nP436rq2p41ldCrybJJaU',
  },
});
// http://cerberus-crm.space/backend/public

// Middleware : ajax : gestion des lettres
const ajaxAdmin = store => next => (action) => {
  const { dispatch } = store;
  switch (action.type) {
    case SEND_LOGIN_REQUEST: {
      const { loginDatas } = action;
      const stringifiedLoginDatas = JSON.stringify(loginDatas);
      axiosUp.post('/api/contact/login', stringifiedLoginDatas)
        .then((response) => {
          const { data } = response;
          console.log(data);
          dispatch(setProfile(data));
        })
        .catch((error) => {
          console.log(error);
        });
      break;
    }

    case SEND_REGISTER_REQUEST: {
      const {
        companyName,
        companySiren,
        companyAddressField,
        companyPostalCode,
        companyCity,
        contactLastname,
        contactFirstname,
        contactBusinessPhone,
        contactEmail,
        contactPassword,
        contactPasswordRepeat,
      } = store.getState().fields.signup;

      const registerDatas = {
        companyName,
        companySiren,
        companyAddressField,
        companyPostalCode,
        companyCity,
        contactLastname,
        contactFirstname,
        contactBusinessPhone,
        contactEmail,
        contactPassword,
        contactPasswordRepeat,
        contactRequest: '',
      };
      const stringifiedRegisterDatas = JSON.stringify(registerDatas);
      axiosUp.post('/api/contact', stringifiedRegisterDatas)
        .then(() => {
          const loginDatas = {
            email: contactEmail,
            password: contactPassword,
          };
          dispatch(sendLoginRequest(loginDatas));
        })
        .catch((error) => {
          console.log(error);
        });
      break;
    }

    case SEND_QUESTION: {
      const {
        title,
        content,
      } = store.getState().fields.question;
      const {
        id,
      } = store.getState().profile;

      const questionDatas = {
        request_title: title,
        request_body: content,
        request_type: 'Devis simple',
      };
      const stringifiedLoginDatas = JSON.stringify(questionDatas);
      axiosUp.post(`/api/contact/${id}/request`, stringifiedLoginDatas)
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
      break;
    }

    default:
      break;
  }

  next(action);
};

export default ajaxAdmin;

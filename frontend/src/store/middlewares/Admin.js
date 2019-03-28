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
  baseURL: 'http://localhost/Apotheose/crm/backend/public',
});
//http://cerberus-crm.space/backend/public

// Middleware : ajax : gestion des lettres
const ajaxAdmin = store => next => (action) => {
  const { dispatch } = store;
  switch (action.type) {
    case SEND_LOGIN_REQUEST: {
      const { loginDatas } = action;
      const stringifiedLoginDatas = JSON.stringify(loginDatas);
      axiosUp.post('/api/login', stringifiedLoginDatas)
        .then((response) => {
          const { data } = response;
          console.log(data);
          const { email, id } = data;
          localStorage.setItem('email', email);
          localStorage.setItem('id', id);
          dispatch(setProfile(id));
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
        logId,
      } = store.getState();

      const questionDatas = {
        request_title: title,
        request_body: content,
        request_type: 'Devis simple',
      };
      const stringifiedLoginDatas = JSON.stringify(questionDatas);
      axiosUp.post(`/api/contact/${logId}/request`, stringifiedLoginDatas)
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

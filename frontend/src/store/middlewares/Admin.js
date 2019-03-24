/**
 * npm import
*/
import axios from 'axios';

/**
 * local import
*/
import { SEND_LOGIN_REQUEST, SEND_REGISTER_REQUEST } from 'src/store/reducer';

/* TODO : redéfinir l'URL du backend en mode production juste avant la fin */

const axiosUp = axios.create({
  baseURL: 'http://127.0.0.1:8001',
});

// Middleware : ajax : gestion des lettres
const ajaxAdmin = store => next => (action) => {
  switch (action.type) {
    case SEND_LOGIN_REQUEST: {
      const { email, password } = store.getState().fields.login;
      const loginDatas = {
        email,
        password,
      };
      const stringifiedLoginDatas = JSON.stringify(loginDatas);
      axiosUp.post('/api/login', stringifiedLoginDatas)
        .then((response) => {
          console.log(response.data);
          /*

          */
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
        contactRequest,
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
        contactRequest,
      };
      console.log(registerDatas);
      /*
        const stringifiedLoginDatas = JSON.stringify(loginDatas);
        axiosUp.post('/api/login', stringifiedLoginDatas)
          .then((response) => {
            console.log(response.data);

          })
          .catch((error) => {
            console.log(error);
          }); */
      break;
    }

    default:
      break;
  }

  next(action);
};

export default ajaxAdmin;

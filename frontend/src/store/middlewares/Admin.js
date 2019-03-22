/**
 * npm import
*/
import axios from 'axios';

/**
 * local import
*/
import { SEND_LOGIN_REQUEST } from 'src/store/reducer';

/* TODO : redéfinir l'URL du backend en mode production juste avant la fin */

const axiosUp = axios.create({
  baseURL: 'http://127.0.0.1:8001',
});

// Middleware : ajax : gestion des lettres
const ajaxAdmin = store => next => (action) => {
  switch (action.type) {
    case SEND_LOGIN_REQUEST: {
      const email = store.getState().fields.login.email;
      const password = store.getState().fields.login.password;
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

    default:
      break;
  }
  next(action);
};

export default ajaxAdmin;

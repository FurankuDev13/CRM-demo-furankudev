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
  SEND_PROFILE_CHANGE,
  updateProfile,
  sendLoginRequest,
  setProfile,
  errorNotification,
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
const ajaxAdmin = store => next => (action) => {
  const { dispatch } = store;
  switch (action.type) {
    case SEND_LOGIN_REQUEST: {
      const { loginDatas } = action;
      const stringifiedLoginDatas = JSON.stringify(loginDatas);
      axiosUp.post('/api/contact/login', stringifiedLoginDatas)
        .then((response) => {
          const { data } = response;
          dispatch(setProfile(data));
        })
        .catch(() => {
          errorNotification();
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
        questionSelect,
      } = store.getState().fields.question;
      const {
        id,
      } = store.getState().profile;
      let type;
      switch (questionSelect) {
        case 'Demande de devis': {
          type = 'Devis simple';
          break;
        }
        default:
          type = 'Informations';
          break;
      }

      const questionDatas = {
        request_title: title,
        request_body: content,
        request_type: type,
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

    case SEND_PROFILE_CHANGE: {
      console.log('youhou');
      const {
        firstname,
        lastname,
        businessPhone,
        cellPhone,
        email,
        password,
      } = store.getState().fields.profile;
      const userProfile = store.getState().profile;
      const { id } = userProfile;
      const personId = userProfile.person.id;
      const profileDatas = {
        id,
        person:
          {
            id: personId,
            firstname,
            lastname,
            businessPhone,
            cellPhone: (cellPhone !== '' ? cellPhone : null),
          },
        password,
        email,
      };
      const stringifiedProfileDatas = JSON.stringify(profileDatas);
      axiosUp.patch(`api/contact/${id}`, stringifiedProfileDatas)
        .then((response) => {
          const { data } = response;
          dispatch(updateProfile(data));
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

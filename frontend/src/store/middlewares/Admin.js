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
    Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1NTM4ODEyNDQsInJvbGVzIjpbIlJPTEVfQVBJVVNFUiJdLCJ1c2VybmFtZSI6ImNlcmJlcnVzLmNybS5tYWlsZXJAZ21haWwuY29tIn0.UxURpuzhDIQg7FijJyS3L387U0eHfeQ0DuYJl2m5vl8FGXrWe160FegbRElhygBB_iXUGUpoYM1t1HvDmMp6vbUU2XxUD-GivCI6Wick0kojqC_m2hXskm-oGCG6XB5BJLC3w4C7oN9bLUsktiiZbQ_DdHAammZ4KixIDbFsb2v_5N3saYy_NRxYG-XKTULL__EQ73OiVIbtGnJZMWIEb-RymcFBDwvn7V3ZlNLQ0DR-jYHHGJv0oGKHOB6jvs2JfwHnAMB6z9CjPrmLKSMlF9bWTmlMvmEmEhG1FxsnphQn8FPXmHn93shFQ8tAdsS479QcN1WeSZYAi38fF7iLyPUi-LcnBmPrZFZZY-j2x82ATHVtSgOu52h7jbPIw-g0Owu5IeHlzi32-vb6BsvMPSC1T1k5cquPUeyab5t6uLf0sVgFQE2DtmSI-dSOCju3-m_oqrTzxsAw3UirlItp8RnkEYbrds2iEa9kEWxwbRasTmGaVxmjaSpgASWq8Bp-YCIMEIQrvBd4jOqFcJWjqrYuA1xv_g73a6zV0edlacz_Vg4Zlb5xq6pRucpQSSLLvxchMRcElY7rNNNjf3l4q7uNB8u1At-lCjlmL4JeN6H8su61k8a4UuzoR1p2yhFg7q3uJuGWoctVHX6B31j33voiPlufBF2ZknEJbjbbNaI',
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

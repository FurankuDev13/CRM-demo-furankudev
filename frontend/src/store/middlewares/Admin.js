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
  displayErrors,
  errorNotification,
} from 'src/store/reducer';

/* TODO : redéfinir l'URL du backend en mode production juste avant la fin */

const axiosUp = axios.create({
  baseURL: 'http://cerberus-crm.space/backend/public',
  headers: {
    Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1NTM4NzIxODQsInJvbGVzIjpbIlJPTEVfQVBJVVNFUiJdLCJ1c2VybmFtZSI6ImNlcmJlcnVzLmNybS5tYWlsZXJAZ21haWwuY29tIn0.PJ9ZVb_3ivHOHm6qRh6mhLZcwAu_AT74YoizZ0kItlZg-WaSMsNQPYQ8o-rDL_E1A6beQqtnEaI3w7u98WBl5Nk1RmEfYjVO_5J-P7LSjrJJ-IsabdGoe39jUcdfU2jYcVjdp0dQnc_gDXD83RGvU9brrEBfeH7vuUtGomUP2pRpgumZDWndzSMRWxrkaye8Wc6XeYnt-qNzzpCDLhHBjWb27x-WIqwYqpbqzjZT_wWlaWe8cfuAOP1DoSWGrb8kW9hsxbXuInSOdsK3oCrX45277eySO7OgmxuI4kAAf68JBQZfZtM2_7CAkmFBld_M2rnlBkiF7vCCMyU-Zu7xUWxguWu2cfDgRpMGAmEP1exAUQ20pYI5BZtQADXRtQdkq6_3HwmhirGYrr325IYpRcPrrhTI1_fAetx-U2wzoeMPHaq5dOn-T8K06h6ZOOWwfSsH-YrJa1ZcHlg0dX5zjj360L_gngPiTiW5T0JdWDkrnp0OAA53n_XFTzTedqVn0P6BXNS5iucB-odh72SEzov-fVHIJO5JocLCXPvjFDncevOfNyDEYc-9l_QwfO6ogjoDRQ6vHr_Y4mDxiQhAmFb5kG8K6hd4Ps_UabJs-Sf26lUK2aWAqrGagUH46pDT0zRh0XV7yE3j_SodI8KPb_BIvEd36vxK5fHJkqRoJzU',
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
        .catch((error) => {
          const errorType = JSON.parse(error.request.responseText).error;
          const errors = [];
          let errorMessage;
          switch (errorType) {
            case 'no_data_sent': {
              errorMessage = 'Le champ email est vide.';
              break;
            }

            case 'no_user_found': {
              errorMessage = 'Vous n\'êtes pas enregistré ou vous avez mal saisi votre mot de passe';
              break;
            }

            default: {
              errorMessage = 'Une erreur de type inconnue est survenue. Veuillez reessayer plus tard';
              break;
            }
          }
          errors.push(errorMessage);
          dispatch(displayErrors(errors));
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
          const errorType = JSON.parse(error.request.responseText).error;
          const errors = [];
          let errorMessage;
          switch (errorType) {
            case 'data_already_exists': {
              errorMessage = JSON.parse(error.request.responseText).error_description;
              break;
            }

            default: {
              errorMessage = 'Une erreur de type inconnue est survenue. Veuillez reessayer plus tard';
              break;
            }
          }
          errors.push(errorMessage);
          dispatch(displayErrors(errors));
          errorNotification();
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

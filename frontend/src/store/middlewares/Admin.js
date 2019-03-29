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
  sendLoginRequest,
  setProfile,
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

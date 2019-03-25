/**
 * Initial State
 */
const initialState = {
  view: 'login',
  isLogged: false,
  catalogList: [],
  fields: {
    login: {
      email: '',
      password: '',
    },
    signup: {
      companyName: '',
      companySiren: '',
      companyAddressField: '',
      companyPostalCode: '',
      companyCity: '',
      contactLastname: '',
      contactFirstname: '',
      contactBusinessPhone: '',
      contactEmail: '',
      contactPassword: '',
      contactPasswordRepeat: '',
      contactRequest: '',
    },
  },
};

/**
 * Types
 */
export const FETCH_CATALOG = 'FETCH_CATALOG';
export const SEND_LOGIN_REQUEST = 'SEND_LOGIN_REQUEST';
export const SEND_REGISTER_REQUEST = 'SEND_REGISTER_REQUEST';
const FETCH_SUCCESS = 'FETCH_SUCCESS';
const INPUT_CHANGE = 'INPUT_CHANGE';

/**
 * Reducer
 */
const reducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case FETCH_SUCCESS:
      return {
        ...state,
        catalogList: [...action.data],
      };
    case INPUT_CHANGE:
      return {
        ...state,
        fields: {
          ...state.fields,
          [action.formOrigin]: {
            ...state.fields[action.formOrigin],
            [action.name]: action.value,
          },
        },
      };

    default:
      return state;
  }
};

/**
 * Action Creators
 */
export const fetchCatalog = () => ({
  type: FETCH_CATALOG,
});

export const fetchSuccess = data => ({
  type: FETCH_SUCCESS,
  data,
});

export const inputChange = (value, formOrigin, name) => ({
  type: INPUT_CHANGE,
  value,
  formOrigin,
  name,
});

export const sendLoginRequest = loginDatas => ({
  type: SEND_LOGIN_REQUEST,
  loginDatas,
});

export const sendRegisterRequest = () => ({
  type: SEND_REGISTER_REQUEST,
});

/**
 * Selectors
 */


/**
 * Export
 */
export default reducer;

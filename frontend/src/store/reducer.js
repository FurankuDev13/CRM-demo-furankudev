/**
 * Import
 */
import { getSlug } from 'src/utils/url';

/**
 * Initial State
 */
const initialState = {
  view: 'login',
  logId: '',
  isLogged: false,
  navbarIsActive: false,
  questionModalIsActive: false,
  categoryList: [],
  catalogList: [],
  articlesOnHomePage: [],
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
    question: {
      title: '',
      content: '',
    },
  },
};

/**
 * Types
 */
export const FETCH_CATALOG = 'FETCH_CATALOG';
export const FETCH_CATEGORIES = 'FETCH_CATEGORIES';
export const FETCH_HOME_PAGE = 'FETCH_HOME_PAGE';
export const SEND_LOGIN_REQUEST = 'SEND_LOGIN_REQUEST';
export const SEND_REGISTER_REQUEST = 'SEND_REGISTER_REQUEST';
export const SEND_QUESTION = 'SEND_QUESTION';
export const SET_PROFILE = 'SET_PROFILE';
const FETCH_SUCCESS = 'FETCH_SUCCESS';
const INPUT_CHANGE = 'INPUT_CHANGE';
const LOGOUT = 'LOGOUT';
const TOGGLE_NAV_BAR = 'TOGGLE_NAV_BAR';
const OPEN_QUESTION_MODAL = 'OPEN_QUESTION_MODAL';
const CLOSE_QUESTION_MODAL = 'CLOSE_QUESTION_MODAL';

/**
 * Reducer
 */
const reducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case FETCH_SUCCESS:
      switch (action.list) {
        case 'catalogList':
          return {
            ...state,
            catalogList: [...action.data],
          };
        case 'categoryList':
          return {
            ...state,
            categoryList: [...action.data],
          };
        case 'articlesOnHomePage':
          return {
            ...state,
            articlesOnHomePage: [...action.data],
          };
        default: return state;
      }
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

    case SET_PROFILE:
      if ((localStorage.getItem('id') !== null)) {
        return {
          ...state,
          logId: localStorage.getItem('id'),
          isLogged: true,
        };
      }
      return {
        ...state,
      };

    case LOGOUT:
      return {
        ...state,
        logEmail: '',
        askQuestionElementIsActive: false,
        isLogged: false,
      };

    case TOGGLE_NAV_BAR:
      return {
        ...state,
        navbarIsActive: !state.navbarIsActive,
      };

    case OPEN_QUESTION_MODAL:
      return {
        ...state,
        questionModalIsActive: true,
      };

    case CLOSE_QUESTION_MODAL:
      return {
        ...state,
        questionModalIsActive: false,
      };

    case SEND_QUESTION:
      return {
        ...state,
        questionModalIsActive: false,
        fields: {
          ...state.fields,
          question: {
            title: '',
            content: '',
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
export const fetchHomePageArticles = () => ({
  type: FETCH_HOME_PAGE,
});

export const fetchCatalog = () => ({
  type: FETCH_CATALOG,
});

export const fetchCategories = () => ({
  type: FETCH_CATEGORIES,
});

export const ProductIsInCategory = (product, slug) => (
  product.categories.find(category => getSlug(category.name) === slug) !== undefined
);

export const getCurrentCategory = (list, slug) => (
  list.filter(product => ProductIsInCategory(product, slug))
);

export const fetchSuccess = (data, list) => ({
  type: FETCH_SUCCESS,
  data,
  list,
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

export const sendQuestion = () => ({
  type: SEND_QUESTION,
});

export const setProfile = profileData => ({
  type: SET_PROFILE,
  profileData,
});

export const logOut = () => ({
  type: LOGOUT,
});

export const toggleNavBar = () => ({
  type: TOGGLE_NAV_BAR,
});

export const openQuestionModal = () => ({
  type: OPEN_QUESTION_MODAL,
});

export const closeQuestionModal = () => ({
  type: CLOSE_QUESTION_MODAL,
});
/**
 * Selectors
 */


/**
 * Export
 */
export default reducer;

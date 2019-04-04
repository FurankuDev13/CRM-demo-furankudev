/**
 * Import
 */
import { getSlug } from 'src/utils/url';

/**
 * Initial State
 */
const initialState = {
  profile: {
    id: '',
    email: '',
    company: {
      name: '',
      description: '',
      picture: 'https://picsum.photos/200',
      sirenNumber: '',
      user: {},
    },
    person: {
      firstname: '',
      lastname: '',
      id: '',
      businessPhone: '',
      cellPhone: '',
    },
  },
  currentProduct: {
    description: '',
    listPrice: 0,
    name: '',
    picture: '',
    reference: '',
  },
  isLogged: false,
  navbarIsActive: false,
  productModalIsActive: false,
  questionModalIsActive: false,
  profileModalIsActive: false,
  categoryList: [],
  catalogList: [],
  articlesOnHomePage: [],
  formErrors: [],
  fields: {
    articleOrder: {
      articleSelect: 'Ordre alphabetique',
    },
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
      questionSelect: 'Demande d\'information',
    },
    profile: {
      firstname: '',
      lastname: '',
      businessPhone: '',
      cellPhone: '',
      password: '',
      passwordRepeat: '',
      email: '',
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
export const SEND_PROFILE_CHANGE = 'SEND_PROFILE_CHANGE';
export const SET_PROFILE = 'SET_PROFILE';
const FETCH_SUCCESS = 'FETCH_SUCCESS';
const INPUT_CHANGE = 'INPUT_CHANGE';
const LOGOUT = 'LOGOUT';
const TOGGLE_NAV_BAR = 'TOGGLE_NAV_BAR';
const TOGGLE_QUESTION_MODAL = 'TOGGLE_QUESTION_MODAL';
const TOGGLE_PROFILE_MODAL = 'TOGGLE_PROFILE_MODAL';
const TOGGLE_PRODUCT_MODAL = 'TOGGLE_PRODUCT_MODAL';
const UPDATE_PROFILE = 'UPDATE_PROFILE';
const DISPLAY_ERRORS = 'DISPLAY_ERRORS';
const DELETE_ERRORS = 'DELETE_ERRORS';
const DISPLAY_ITEM = 'DISPLAY_ITEM';


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

    case SET_PROFILE: {
      const { data } = action;
      return {
        ...state,
        profile: {
          id: data.id,
          email: data.email,
          company: {
            name: data.company.name,
            description: data.company.description,
            picture: data.company.picture,
            sirenNumber: data.company.sirenNumber,
            user: (data.company.user !== null ? {
              firstname: data.company.user.person.firstname,
              email: data.company.user.email,
              lastname: data.company.user.person.lastname,
              businessPhone: data.company.user.person.businessPhone,
            } : {}),
          },
          person: {
            id: data.person.id,
            firstname: data.person.firstname,
            lastname: data.person.lastname,
            businessPhone: data.person.businessPhone,
            cellPhone: data.person.cellPhone,
          },
        },
        isLogged: true,
      };
    }

    case UPDATE_PROFILE: {
      const { data } = action;
      const {
        firstname,
        lastname,
        cellPhone,
        businessPhone,
      } = data.person;
      return {
        ...state,
        profileModalIsActive: false,
        profile: {
          ...state.profile,
          email: data.email,
          person: {
            ...state.profile.person,
            firstname,
            lastname,
            businessPhone,
            cellPhone,
          },
        },
      };
    }

    case LOGOUT:
      return {
        ...state,
        profile: {
          id: '',
          email: '',
          company: {
            name: '',
            description: '',
            picture: 'https://picsum.photos/200',
            sirenNumber: '',
            user: {},
          },
          person: {
            firstname: '',
            lastname: '',
            id: '',
            businessPhone: '',
            cellPhone: '',
          },
        },
        isLogged: false,
      };

    case TOGGLE_NAV_BAR:
      return {
        ...state,
        navbarIsActive: !state.navbarIsActive,
      };

    case TOGGLE_QUESTION_MODAL:
      return {
        ...state,
        questionModalIsActive: !state.questionModalIsActive,
      };

    case TOGGLE_PRODUCT_MODAL:
      return {
        ...state,
        productModalIsActive: !state.productModalIsActive,
      };

    case TOGGLE_PROFILE_MODAL:
      return {
        ...state,
        profileModalIsActive: !state.profileModalIsActive,
        fields: {
          ...state.fields,
          profile: {
            firstname: state.profile.person.firstname,
            lastname: state.profile.person.lastname,
            businessPhone: state.profile.person.businessPhone,
            cellPhone: (state.profile.person.cellPhone ? state.profile.person.cellPhone : ''),
            password: '',
            passwordRepeat: '',
            email: state.profile.email,
          },
        },
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
            questionSelect: 'Demande d\'information',
          },
        },
      };

    case DISPLAY_ERRORS:
      return {
        ...state,
        formErrors: [
          ...action.errorArray,
        ],
      };

    case DELETE_ERRORS:
      return {
        ...state,
        formErrors: [],
      };


    case DISPLAY_ITEM: {
      const {
        description,
        listPrice, name,
        picture,
        reference,
      } = action.itemProps;
      return {
        ...state,
        productModalIsActive: true,
        currentProduct: {
          description,
          listPrice,
          name,
          picture,
          reference,
        },
      };
    }

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

export const getCategoryFromSlug = (list, slug) => (
  list.find(category => getSlug(category.name) === slug).name
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

export const sendProfileChange = () => ({
  type: SEND_PROFILE_CHANGE,
});

export const setProfile = data => ({
  type: SET_PROFILE,
  data,
});

export const logOut = () => ({
  type: LOGOUT,
});

export const toggleNavBar = () => ({
  type: TOGGLE_NAV_BAR,
});

export const toggleQuestionModal = () => ({
  type: TOGGLE_QUESTION_MODAL,
});

export const toggleProfileModal = () => ({
  type: TOGGLE_PROFILE_MODAL,
});

export const toggleProductModal = () => ({
  type: TOGGLE_PRODUCT_MODAL,
});

export const updateProfile = data => ({
  type: UPDATE_PROFILE,
  data,
});

export const displayErrors = errorArray => ({
  type: DISPLAY_ERRORS,
  errorArray,
});

export const errorNotification = () => {
  document.getElementById('notification').className = 'notification is-danger';
};

export const deleteNotification = () => {
  document.getElementById('notification').className += ' is-hidden';
};

export const deleteErrors = () => ({
  type: DELETE_ERRORS,
});

export const displayItem = itemProps => ({
  type: DISPLAY_ITEM,
  itemProps,
});

export const popMessage = (message, style) => {
  const div = document.createElement('div');
  div.className = `notification is-${style} is-centered`;
  div.innerText = message;
  const popmessage = document.getElementById('popmessage');
  popmessage.appendChild(div);
  setTimeout(() => {
    popmessage.removeChild(div);
  }, 3000);
};
/**
 * Selectors
 */


/**
 * Export
 */
export default reducer;

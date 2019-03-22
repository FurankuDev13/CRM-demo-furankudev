/**
 * Initial State
 */
const initialState = {
  view: 'login',
  isLogged: false,
  catalogList: [],
};

/**
 * Types
 */
export const FETCH_CATALOG = 'FETCH_CATALOG';
const FETCH_SUCCESS = 'FETCH_SUCCESS';

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

/**
 * Selectors
 */


/**
 * Export
 */
export default reducer;

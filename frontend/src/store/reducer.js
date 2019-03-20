/**
 * Initial State
 */
const initialState = {
  isLogged: false,
  catalogList: [],
};

/**
 * Types
 */
const FETCH_CATALOG = 'FETCH_CATALOG';

/**
 * Reducer
 */
const reducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case FETCH_CATALOG:
      return {
        ...state,
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
  message,
});

/**
 * Selectors
 */


/**
 * Export
 */
export default reducer;

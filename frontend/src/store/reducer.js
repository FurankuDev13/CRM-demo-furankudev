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
const DO_SOMETHING = 'DO_SOMETHING';

/**
 * Reducer
 */
const reducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case DO_SOMETHING:
      return {
        ...state,
        message: action.message,
      };

    default:
      return state;
  }
};

/**
 * Action Creators
 */
export const doSomething = message => ({
  type: DO_SOMETHING,
  message,
});

/**
 * Selectors
 */


/**
 * Export
 */
export default reducer;

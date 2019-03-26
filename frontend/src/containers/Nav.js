/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Nav from 'src/components/Nav';
import { logOut, toggleNavBar } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = (state) => {
  const { isLogged, logEmail, navbarIsActive } = state;
  return ({
    isLogged,
    logEmail,
    navbarIsActive,
  });
};

const mapDispatchToProps = dispatch => ({
  logOut: () => {
    localStorage.removeItem('email');
    dispatch(logOut());
  },
  toggleNavBar: () => {
    dispatch(toggleNavBar());
  },
});

const CatalogContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Nav);

/**
 * Export
 */
export default CatalogContainer;

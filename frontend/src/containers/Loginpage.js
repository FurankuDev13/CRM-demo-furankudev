/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Loginpage from 'src/components/Loginpage';

/**
 * Mapping
 */
const mapStateToProps = state => ({
  loginFields: state.fields.login,
  formErrors: state.formErrors,
});

const mapDispatchToProps = () => ({});

const LoginpageContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Loginpage);

/**
 * Export
 */
export default LoginpageContainer;

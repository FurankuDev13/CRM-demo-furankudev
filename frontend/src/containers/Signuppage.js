/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Signuppage from 'src/components/Signuppage';

/**
 * Mapping
 */
const mapStateToProps = state => ({
  signupFields: state.fields.signup,
});

const mapDispatchToProps = () => ({});

const SignuppageContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Signuppage);

/**
 * Export
 */
export default SignuppageContainer;

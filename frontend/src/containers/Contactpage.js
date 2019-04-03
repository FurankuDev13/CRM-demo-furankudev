/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Contactpage from 'src/components/Contactpage';

/**
 * Mapping
 */
const mapStateToProps = state => ({
  commercial: state.profile.company.user,
});

const mapDispatchToProps = () => ({});

const ContactpageContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Contactpage);

/**
 * Export
 */
export default ContactpageContainer;

/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import ProfileForm from 'src/components/Profilepage/ProfileForm';
import { toggleProfileModal } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = state => ({
  profileFields: state.fields.profile,
  formErrors: state.formErrors,
});

const mapDispatchToProps = dispatch => ({
  toggleProfileModal: () => {
    dispatch(toggleProfileModal());
  },
});

const ProfileFormContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(ProfileForm);

/**
 * Export
 */
export default ProfileFormContainer;

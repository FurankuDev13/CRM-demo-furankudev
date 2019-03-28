/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Profilepage from 'src/components/Profilepage';
import { toggleProfileModal } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = (state) => {
  const { profile } = state;
  const { person, company } = profile;
  return ({
    ...person,
    ...company,
  });
};

const mapDispatchToProps = dispatch => ({
  toggleProfileModal: () => {
    dispatch(toggleProfileModal());
  },
});

const ProfilepageContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Profilepage);

/**
 * Export
 */
export default ProfilepageContainer;

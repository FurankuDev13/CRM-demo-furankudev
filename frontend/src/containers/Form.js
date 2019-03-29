/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Form from 'src/components/Form';

// Action Creators
import {
  sendLoginRequest,
  sendRegisterRequest,
  sendQuestion,
  sendProfileChange,
} from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = () => ({});

const mapDispatchToProps = (dispatch, ownProps) => ({
  submitForm: () => {
    const { formOrigin } = ownProps;
    switch (formOrigin) {
      case 'login': {
        const email = ownProps.tabl.find(element => element.name === 'email').value;
        const password = ownProps.tabl.find(element => element.name === 'password').value;
        const loginDatas = {
          email,
          password,
        };
        dispatch(sendLoginRequest(loginDatas));
      }
        break;
      case 'signup':
        dispatch(sendRegisterRequest());
        break;

      case 'question':
        dispatch(sendQuestion());
        break;

      case 'profile':
        dispatch(sendProfileChange());
        break;

      default:
    }
  },
});

const FormContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Form);

/**
 * Export
 */
export default FormContainer;

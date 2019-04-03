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
  displayErrors,
  errorNotification,
  deleteErrors,
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
      case 'signup': {
        const errors = [];
        const companySiren = ownProps.tabl[0].value;
        const companyName = ownProps.tabl[1].value;
        const companyAdress = ownProps.tabl[2].value;
        const companyPostalCode = ownProps.tabl[3].value;
        const companyCity = ownProps.tabl[4].value;
        const contactFirstname = ownProps.tabl[5].value;
        const contactLastname = ownProps.tabl[6].value;
        const contactBusinessPhone = ownProps.tabl[7].value;
        const contactEmail = ownProps.tabl[8].value;
        const contactPassword = ownProps.tabl[9].value;
        const contactPasswordRepeat = ownProps.tabl[10].value;
        if (companySiren.length !== 9) {
          errors.push('Un numéro de siren doit comporter 9 chiffres');
        }

        if (companyName.length === 0) {
          errors.push('Veuillez renseigner le nom de votre entreprise');
        }

        if (companyAdress.length === 0) {
          errors.push('Veuillez renseigner l\'adresse de votre entreprise');
        }

        if (companyPostalCode.length === 0) {
          errors.push('Veuillez renseigner le code postal de votre entreprise');
        }

        if (companyCity.length === 0) {
          errors.push('Veuillez renseigner la ville où se situe votre entreprise');
        }

        if (contactFirstname.length === 0) {
          errors.push('Veuillez renseigner votre prénom');
        }

        if (contactLastname.length === 0) {
          errors.push('Veuillez renseigner votre nom');
        }

        if (contactBusinessPhone.length === 0) {
          errors.push('Veuillez nous fournir un numéro sur lequel nous pouvons vous contacter');
        }
        else if (contactBusinessPhone.length !== 10) {
          errors.push('Numéro mal renseigné ou incomplet');
        }

        if (contactEmail.length === 0) {
          errors.push('Veuillez nous fournir une adresse mail sur laquelle nous pouvons vous contacter');
        }
        if (contactPassword.length === 0) {
          errors.push('Veuillez ajouter un mot de passe');
        }
        else if (contactPassword !== contactPasswordRepeat) {
          errors.push('Vos champs de mot de passe et confirmation sont différents. Soyez sûr(e) de votre saisie');
        }
        if (errors.length !== 0) {
          dispatch(displayErrors(errors));
          dispatch(errorNotification());
        }
        else {
          deleteErrors();
          dispatch(sendRegisterRequest());
        }
        break;
      }

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

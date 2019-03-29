/**
 * Npm import
 */
import { connect } from 'react-redux';

/**
 * Local import
 */
import Homepage from 'src/components/Homepage';
import { fetchHomePageArticles } from 'src/store/reducer';

/**
 * Mapping
 */
const mapStateToProps = state => ({
  articlesOnHomePage: state.articlesOnHomePage,
});

const mapDispatchToProps = dispatch => ({
  fetchHomePageArticles: () => {
    dispatch(fetchHomePageArticles());
  },
});

const HomepageContainer = connect(
  mapStateToProps,
  mapDispatchToProps,
)(Homepage);


/**
 * Export
 */
export default HomepageContainer;

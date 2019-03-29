/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';

/**
 * Local import
 */
import Display from './Display';
import './homepage.scss';

class Homepage extends React.Component {
  componentDidMount() {
    const { fetchHomePageArticles } = this.props;
    fetchHomePageArticles();
  }

  render() {
    const { articlesOnHomePage } = this.props;
    return (
      <main>
        <div className="columns is-gapless is-spaced">
          <div className="column is-half">
            <section className="hero is-bold is-medium">
              <div className="hero-body">
                <h1 className="title is-2 is-spaced has-text-primary is-uppercase has-text-weight-bold">
                  o'Beer
                </h1>
                <p className="subtitle is-4 is-spaced">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consectetur quod esse perferendis animi soluta, quia ea deserunt vero minus magnam fugit autem porro inventore ipsam exercitationem corrupti et cumque dolorum.
                </p>
                <p className="subtitle is-4 is-spaced">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consectetur quod esse perferendis animi soluta, quia ea deserunt vero minus magnam fugit autem porro inventore ipsam exercitationem corrupti et cumque dolorum.
                </p>
                <div className="level">
                  <Link to="/signup" className="link is-info">
                    <button className="level-item has-text-centered button is-primary is-outlined is-medium" type="button">
                      Créer un compte
                    </button>
                  </Link>
                  <p className="level-item has-text-centered is-medium">
                    <span className="is-size-5">Déjà client ? &nbsp;</span><Link to="/login" className="link is-info is-size-5">Se connecter</Link>
                  </p>
                </div>
              </div>
            </section>
          </div>
          <div id="right" className="column is-half">
            <section className="hero is-large">
              <div className="hero-body" />
            </section>
          </div>
        </div>
        { articlesOnHomePage.length !== 0 && (
          <div className="tile is-ancestor">
            <div className="tile is-parent is-vertical">
              {
                <>
                  <Display
                    {...articlesOnHomePage[0]}
                    classes="tile is-child"
                  />
                  <Display
                    {...articlesOnHomePage[1]}
                    classes="tile is-child"
                  />
                </>
              }
            </div>
            <div className="tile is-parent is-6">
              {
                <Display
                  {...articlesOnHomePage[2]}
                  classes="tile is-child notification is-primary"
                />
              }
            </div>
            <div className="tile is-parent is-vertical">
              {
                <>
                  <Display
                    {...articlesOnHomePage[3]}
                    classes="tile is-child"
                  />
                  <Display
                    {...articlesOnHomePage[4]}
                    classes="tile is-child"
                  />
                </>
              }
            </div>
          </div>
        )
        }
        <section className="hero is-normal is-bold has-text-centered">
          <div className="hero-body">
            <div className="container">
              <h2 className="title is-2 is-spaced has-text-primary is-uppercase has-text-weight-bold">
                Nous contacter
              </h2>
              <p className="title is-3 has-text-weight-bold">
                o'Wine'rs
              </p>
              <p className="is-size-4">10 rue de Penthievre</p>
              <p className="is-size-4">75008 Paris</p>
              <p className="is-size-4">hey@oclock.io</p>
              <p className="is-size-4">09.74.76.80.67</p>
            </div>
          </div>
        </section>
      </main>
    );
  }
}

Homepage.propTypes = {
  fetchHomePageArticles: PropTypes.func.isRequired,
  articlesOnHomePage: PropTypes.array.isRequired,
};

/**
 * Export
 */
export default Homepage;

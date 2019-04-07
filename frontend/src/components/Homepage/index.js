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
import Companyaddress from './Companyaddress';
import './homepage.scss';

class Homepage extends React.Component {
  componentDidMount() {
    const { fetchHomePageArticles } = this.props;
    fetchHomePageArticles();
  }

  render() {
    const { articlesOnHomePage } = this.props;
    return (
      <main className="container">
        <div className="columns is-gapless is-spaced">
          <div className="column is-half">
            <section className="hero is-bold is-medium">
              <div className="hero-body">
                <h1 className="title is-2 is-spaced has-text-primary is-uppercase has-text-weight-bold">
                  Beer O'clock, les meilleures bières au meilleur prix pour votre commerce !
                </h1>
                <p className="subtitle is-4 is-spaced">
                Beer o'Clock c’est une grande famille, une très grande famille, qui a pour principales valeurs le partage et la bière au sens large (oui oui, on considère la bière comme une valeur). Chaque jour, ce sont 15 personnes qui travaillent main dans la main (au sens figuré bien entendu) pour proposer aux amateurs de bière des produits qu’il est difficile, voire impossible, de trouver dans les supermarchés classiques. 
                </p>
                <p className="subtitle is-4 is-spaced">
                  Il y a notre acheteur de bière, notre préparateur de commande de bière, notre service client qui parle bière… que des passionnés qui sont là pour assurer votre expérience houblonnée. Mais surtout, il y a vous, qui rendez cette aventure possible depuis plus de 10 ans.
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
        <Companyaddress />
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

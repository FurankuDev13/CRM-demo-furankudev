/**
 * NPM import
 */
import React from 'react';
import { Link } from 'react-router-dom';

/**
 * Local import
 */
import './homepage.scss';

const Homepage = () => (
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
              <button className="level-item has-text-centered button is-primary is-outlined is-medium" type="button">
                <Link to="/signup" className="link is-info">Créer un compte</Link>
              </button>
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
    <div className="tile is-ancestor">
      <div className="tile is-parent is-vertical">
        <article className="tile is-child">
          <p className="title">Millésimes italiens</p>
          <p className="subtitle">Sélection</p>
          <figure className="image is-4by3">
            <img src="https://wineblots.com/wp-content/uploads/2018/02/jehyun-sung-477890-1.jpg" alt="Millésimes italiens" />
          </figure>
        </article>
        <article className="tile is-child">
          <p className="title">Pépites d'Amérique</p>
          <p className="subtitle">Sélection</p>
          <figure className="image is-4by3">
            <img src="https://playboy.co.za/wp-content/uploads/2017/11/crash-course-640x400.jpg" alt="Pépites d'Amérique" />
          </figure>
        </article>
      </div>
      <div className="tile is-parent is-6">
        <article className="tile is-child notification is-primary">
          <p className="title">Crus français</p>
          <p className="subtitle">Sélection</p>
          <figure className="image is-4by3">
            <img src="https://secure.i.telegraph.co.uk/multimedia/archive/01371/pwine1_1371910c.jpg" alt="Crus français" />
          </figure>
        </article>
      </div>
      <div className="tile is-parent is-vertical">
        <article className="tile is-child">
          <p className="title">Made in Shiba</p>
          <p className="subtitle">Sélection</p>
          <figure className="image is-4by3">
            <img src="https://www.summitdaily.com/wp-content/uploads/sites/2/2016/08/wineink-swift-081516-1.jpg" alt="Made in Shiba" />
          </figure>
        </article>
        <article className="tile is-child">
          <p className="title">Cépages du Tanuki</p>
          <p className="subtitle">Sélection</p>
          <figure className="image is-4by3">
            <img src="https://gimmethegoodstuff.org/wp-content/uploads/Wine-subscription-gimme-the-Good-Stuff.jpeg" alt="Cépages du Tanuki" />
          </figure>
        </article>
      </div>
    </div>
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
/**
 * TODO :
 * - ajouter des alts appropriés lorsqu'on aura ajouté de vrais articles / catégories
 */

/**
 * Export
 */
export default Homepage;

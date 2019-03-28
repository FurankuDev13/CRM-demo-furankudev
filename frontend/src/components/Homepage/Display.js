/**
 * NPM import
 */
import React from 'react';
import PropTypes from 'prop-types';

/**
 * local import
 */

/**
 * Code
 */

const Display = ({ classes, name, picture }) => (
  <article className={classes}>
    <p className="title">{name}</p>
    <figure className="image is-4by3">
      <img src={picture} alt={name} />
    </figure>
  </article>
);

Display.propTypes = {
  classes: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  picture: PropTypes.string.isRequired,
};

/**
 * Export
 */
export default Display;

/*
<article className="tile is-child">
<p className="title">Millésimes italiens</p>
<figure className="image is-4by3">
<img src="https://wineblots.com/wp-content/uploads/2018/02/jehyun-sung-477890-1.jpg" alt="Millésimes italiens" />
</figure>
</article>
<article className="tile is-child">
<p className="title">Pépites d'Amérique</p>
<figure className="image is-4by3">
<img src="https://playboy.co.za/wp-content/uploads/2017/11/crash-course-640x400.jpg" alt="Pépites d'Amérique" />
</figure>
</article>

<article className="tile is-child notification is-primary">
<p className="title">Crus français</p>
<figure className="image is-4by3">
<img src="https://secure.i.telegraph.co.uk/multimedia/archive/01371/pwine1_1371910c.jpg" alt="Crus français" />
</figure>
</article>

<article className="tile is-child">
<p className="title">Made in Shiba</p>
<figure className="image is-4by3">
<img src="https://www.summitdaily.com/wp-content/uploads/sites/2/2016/08/wineink-swift-081516-1.jpg" alt="Made in Shiba" />
</figure>
</article>
<article className="tile is-child">
<p className="title">Cépages du Tanuki</p>
<figure className="image is-4by3">
<img src="https://gimmethegoodstuff.org/wp-content/uploads/Wine-subscription-gimme-the-Good-Stuff.jpeg" alt="Cépages du Tanuki" />
</figure>
</article> */

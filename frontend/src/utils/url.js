import slugify from 'slugify';

export const getSlug = string => slugify(string, {
  lower: true,
  remove: /[*+~.()'"!:@]/g,
});

export const getURL = (prefix, name) => `${prefix}/${getSlug(name)}`;

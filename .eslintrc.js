module.exports = {
  env: {
    browser: true,
    es6: true,
  },
  extends: [
    'airbnb-base',
  ],
  globals: {
    Atomics: 'readonly',
    SharedArrayBuffer: 'readonly',
    algolia: 'readonly',
    autocomplete: 'readonly',
    algoliasearch: 'readonly',
    instantsearch: 'readonly',
    tippy: 'readonly',
    wp: 'readonly',
    jQuery: 'readonly',
    wikiDict: 'readonly'
  },
  parserOptions: {
    ecmaVersion: 2018,
  },
  rules: {
    "func-names": "off",
    "no-console": "off",
    "no-mixed-operators": "off",
    "no-param-reassign": "off",
    "no-plusplus": "off",
    "no-restricted-syntax": "off",
    "no-underscore-dangle": "off"
  },
};

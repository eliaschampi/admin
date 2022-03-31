module.exports = {
  root: true,
  env: {
    node: true,
    browser: true,
    es2021: true,
    jquery: true,
    commonjs: true
  },
  extends: ["plugin:vue/essential", "eslint:recommended"],
  rules: {
    "no-console": "error",
    "no-debugger": "error",
    eqeqeq: "error",
    "no-invalid-this": "error",
    "no-return-assign": "error",
    "no-unused-expressions": ["error", { allowTernary: true }],
    "no-useless-concat": "error",
    "no-useless-return": "error",

    // Variable
    "init-declarations": "error",
    "no-use-before-define": "error",

    // Stylistic Issues
    "array-bracket-newline": ["error", { multiline: true, minItems: null }],
    "array-bracket-spacing": "error",
    "brace-style": ["error", "1tbs", { allowSingleLine: true }],
    "block-spacing": "error",
    "comma-dangle": "error",
    "comma-spacing": "error",
    "comma-style": "error",
    "computed-property-spacing": "error",
    "func-call-spacing": "error",
    indent: ["error", 2],
    "keyword-spacing": "error",
    "no-lonely-if": "error",
    "no-mixed-operators": "error",
    "no-multiple-empty-lines": ["error", { max: 2, maxEOF: 1 }],
    "no-tabs": "error",
    "no-unneeded-ternary": "error",
    "no-whitespace-before-property": "error",
    "object-property-newline": [
      "error",
      { allowAllPropertiesOnSameLine: true }
    ],
    quotes: ["error", "double"],
    semi: ["error", "always"],
    "semi-spacing": "error",
    "space-before-blocks": "error",
    "space-in-parens": "error",
    "space-infix-ops": "error",
    "space-unary-ops": "error",

    // ES6
    "arrow-spacing": "error",
    "no-confusing-arrow": "error",
    "no-duplicate-imports": "error",
    "no-var": "error",
    "object-shorthand": "error",
    "prefer-const": "error",
    "prefer-template": "error",

    // Vue
    "vue/html-quotes": ["error", "double"],
    "vue/multi-word-component-names": "off"
  },
  parserOptions: {
    parser: "@babel/eslint-parser",
    "requireConfigFile": false,
    sourceType: "module"
  }
};

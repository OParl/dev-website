{

  'parserOptions': {
    'ecmaVersion': 6,
    'sourceType': 'script'
  },

  'env': {
    'browser': true,
    'es6': true,
    'node': true
  },

  'rules': {
    'class-methods-use-this': ['error', {
      exceptMethods: [],
    }],
    'consistent-return': 'warn',
    'curly': ['error', 'all'],
    'default-case': ['error', { commentPattern: '^no default$' }],
    'eqeqeq': ['error', 'allow-null'],
    'no-alert': 'error',
    'no-caller': 'error',
    'no-cond-assign': 'error',
    'no-dupe-args': 'error',
    'no-dupe-keys': 'error',
    'no-duplicate-case': 'error',
    'no-empty-character-class': 'error',
    'no-empty': 'error',
    'no-empty-function': ['error', {
      allow: [
        'arrowFunctions',
        'functions',
        'methods',
      ]
    }],
    'no-eq-null': 'error',
    'no-eval': 'error',
    'no-ex-assign': 'error',
    'no-extra-semi': 'error',
    'no-func-assign': 'error',
    'no-global-assign': ['error', { exceptions: [] }],
    'quotes': ['error', 'single'],
  }

}
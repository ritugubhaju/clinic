import js from '@eslint/js';
import pluginVue from 'eslint-plugin-vue';
import prettierConfig from '@vue/eslint-config-prettier';

export default [
    js.configs.recommended,
    ...pluginVue.configs['flat/recommended'],
    prettierConfig,
    {
        languageOptions: {
            ecmaVersion: 'latest',
            sourceType: 'module',
            globals: {
                route: 'readonly',
            },
        },
        rules: {
            'vue/multi-word-component-names': 'off',
            'no-unused-vars': ['warn', { argsIgnorePattern: '^_' }],
        },
    },
    {
        ignores: ['vendor/**', 'node_modules/**', 'public/build/**', 'storage/**'],
    },
];

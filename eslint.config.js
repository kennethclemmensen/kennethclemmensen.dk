import typescriptEslint from '@typescript-eslint/eslint-plugin';
import typescriptParser from '@typescript-eslint/parser';

export default [
	{
		plugins: {
			typescriptEslint
		},
		languageOptions: {
			parser: typescriptParser,
			parserOptions: {
				ecmaVersion: 12,
        		sourceType: 'module'
			}
		},
		rules: {
			'no-undef': 'off',
			'no-unused-vars': 'off',
			'prefer-const': 'warn',
			'semi': ['warn', 'always'],
			'quotes': ['warn', 'single'],
			'no-console': 'warn',
			'typescriptEslint/naming-convention': [
				'warn',
				{
					'selector': ['class', 'enum', 'typeAlias'],
					'format': ['PascalCase'],
					'leadingUnderscore': 'forbid'
				},
				{
					'selector': 'classProperty',
					'format': ['camelCase'],
					'leadingUnderscore': 'forbid'
				}
			]
		}
	}
];
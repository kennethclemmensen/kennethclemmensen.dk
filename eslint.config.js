import tseslint from 'typescript-eslint';
import stylistic from '@stylistic/eslint-plugin';
import { defineConfig, globalIgnores } from 'eslint/config';

export default defineConfig([
	globalIgnores([
		'**/*.js',
		'**/*.cjs',
		'public/wp-content/plugins/**/*.ts',
		'**/*.tsx'
	]),
	{
		extends: [
			tseslint.configs.recommended
		],
		files: [
			'public/wp-content/themes/kennethclemmensen/ts/**/*.ts'
		],
		plugins: {
			'@stylistic': stylistic
		},
		rules: {
			'@stylistic/padding-line-between-statements': ['warn', {
				blankLine: 'always',
				prev: ['const', 'let'],
				next: 'if'
			}],
			'@stylistic/space-before-function-paren': ['warn', {
				'anonymous': 'never',
				'named': 'never',
				'asyncArrow': 'never',
				'catch': 'never'
			}],
			'@stylistic/type-annotation-spacing': ['warn', {
				'before': true,
				'after': true,
				'overrides': {
					'colon': {
						'before': false,
						'after': true
					}
				}
			}],
			'@typescript-eslint/naming-convention': ['warn', {
                'selector': ['class', 'enum', 'typeAlias'],
                'format': ['PascalCase'],
                'leadingUnderscore': 'forbid'
            }, {
                'selector': 'classProperty',
                'format': ['camelCase'],
                'leadingUnderscore': 'forbid'
            }],
			'@typescript-eslint/no-this-alias': 'off',
			'prefer-const': 'warn',
			'quotes': ['warn', 'single'],
			'semi': ['warn', 'always']
		}
	}
]);
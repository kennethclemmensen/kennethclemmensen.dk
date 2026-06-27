import eslint from '@eslint/js';
import tseslint from 'typescript-eslint';
import stylistic from '@stylistic/eslint-plugin';
import { defineConfig } from 'eslint/config';

export default defineConfig(
	eslint.configs.recommended,
  	...tseslint.configs.strictTypeChecked,
	{
		languageOptions: {
			parserOptions: {
				projectService: true
			}
		},
		plugins: {
			'@stylistic': stylistic
		},
		rules: {
			'no-undef': 'off',
			'no-unused-vars': 'off',
			'prefer-const': 'warn',
			'semi': ['warn', 'always'],
			'quotes': ['warn', 'single'],
			'no-console': ['warn', { allow: ['error'] }],
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
			'@stylistic/padding-line-between-statements': ['warn',
				{ blankLine: 'always', prev: ['const', 'let'], next: 'if' }
			],
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
			'@typescript-eslint/no-explicit-any': 'warn',
			'@typescript-eslint/no-import-type-side-effects': 'warn',
			'@typescript-eslint/no-invalid-this': 'warn',
			'@typescript-eslint/no-misused-new': 'warn',
			'@typescript-eslint/no-non-null-assertion': 'warn',
			'@typescript-eslint/no-unnecessary-parameter-property-assignment': 'warn',
			'@typescript-eslint/prefer-readonly': 'warn',
			'@typescript-eslint/restrict-plus-operands': 'off',
			'@typescript-eslint/restrict-template-expressions': 'off',
			'@typescript-eslint/no-unsafe-assignment': 'off',
			'@typescript-eslint/no-unsafe-member-access': 'off',
			'@typescript-eslint/no-unsafe-call': 'off',
			'@typescript-eslint/no-unsafe-argument': 'off',
			'@typescript-eslint/no-unsafe-enum-comparison': 'off',
			'@typescript-eslint/no-floating-promises': 'off'
		}
	}
);
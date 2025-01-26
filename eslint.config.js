import eslint from '@eslint/js';
import tseslint from 'typescript-eslint';
import onlyWarn from 'eslint-plugin-only-warn';
import stylisticJs from '@stylistic/eslint-plugin-js';

export default tseslint.config(
	eslint.configs.recommended,
  	...tseslint.configs.strict,
	{
		plugins: {
			onlyWarn,
			'@stylistic/js': stylisticJs
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
			'@stylistic/js/padding-line-between-statements': ['warn',
				{ blankLine: 'always', prev: ['const', 'let'], next: 'if' }
			]
		}
	}
);
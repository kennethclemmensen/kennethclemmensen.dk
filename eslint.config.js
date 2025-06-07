import eslint from '@eslint/js';
import tseslint from 'typescript-eslint';
import onlyWarn from 'eslint-plugin-only-warn';
import stylistic from '@stylistic/eslint-plugin';

export default tseslint.config(
	eslint.configs.recommended,
  	...tseslint.configs.strict,
	{
		plugins: {
			onlyWarn,
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
			]
		}
	}
);
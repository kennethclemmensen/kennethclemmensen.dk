import eslint from '@eslint/js';
import tseslint from 'typescript-eslint';
import onlyWarn from 'eslint-plugin-only-warn';

export default tseslint.config(
	eslint.configs.recommended,
  	...tseslint.configs.strict,
	{
		plugins: {
			onlyWarn
		},
		rules: {
			'no-undef': 'off',
			'no-unused-vars': 'off',
			'prefer-const': 'warn',
			'semi': ['warn', 'always'],
			'quotes': ['warn', 'single'],
			'no-console': 'warn'
		}
	}
);
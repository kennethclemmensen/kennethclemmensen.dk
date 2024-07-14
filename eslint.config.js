import eslint from '@eslint/js';
import tseslint from 'typescript-eslint';

export default tseslint.config(
	eslint.configs.recommended,
  	...tseslint.configs.strict,
	{
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
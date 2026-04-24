module.exports = {
	extends: '@wordpress/stylelint-config/scss',
	rules: {
		'declaration-no-important': true,
		'no-empty-source': null,
		'no-descending-specificity': null,
		'selector-pseudo-class-no-unknown': [
			true,
			{
				ignorePseudoClasses: [ 'global' ],
			},
		],
	},
};

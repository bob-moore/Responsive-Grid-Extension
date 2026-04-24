/**
 * This is the new data shape
 *
 * @param settings Block settings.
 * @param name     Block name.
 */
export const addResponsiveGridAttributes = ( settings, name: string ) => {
	if ( 'core/group' === name ) {
		settings = {
			...settings,
			attributes: {
				...settings.attributes,
				responsiveGridColumns: {
					type: 'object',
					default: {
						desktop: '',
						tablet: '',
						mobile: '',
					},
				},
				responsiveGridRows: {
					type: 'object',
					default: {
						desktop: '',
						tablet: '',
						mobile: '',
					},
				},
			},
		};
	}
	return settings;
};

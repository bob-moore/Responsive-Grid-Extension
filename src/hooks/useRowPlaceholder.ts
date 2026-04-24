import { useMemo } from '@wordpress/element';
import isEmpty from '../utilities/isEmpty';

type PlaceholderTemplate = {
	desktop: string;
	tablet: string;
	mobile: string;
};

export const useRowPlaceholder = (
	rows: PlaceholderTemplate
): PlaceholderTemplate => {
	return useMemo( () => {
		const defaultPlaceholder = 'auto';
		// --- Mobile Logic (Fallback order: tablet -> desktop -> default) ---
		let mobileValue = defaultPlaceholder;

		// Prioritize desktop template if it exists
		if ( ! isEmpty( rows.desktop ) ) {
			mobileValue = rows.desktop;
		}
		// Overwrite with tablet template if it exists (higher priority)
		if ( ! isEmpty( rows.tablet ) ) {
			mobileValue = rows.tablet;
		}

		return {
			mobile: mobileValue,
			tablet: ! isEmpty( rows.desktop )
				? rows.desktop
				: defaultPlaceholder,
			desktop: defaultPlaceholder,
		};
	}, [ rows ] );
};

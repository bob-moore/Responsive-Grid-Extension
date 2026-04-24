import { useMemo } from '@wordpress/element';
import isEmpty from '../utilities/isEmpty';

type PlaceholderTemplate = {
	desktop: string;
	tablet: string;
	mobile: string;
};

export const useColumnPlaceholder = (
	columns: PlaceholderTemplate,
	columnCount?: number
): PlaceholderTemplate => {
	return useMemo( () => {
		const defaultPlaceholder = `repeat(${ columnCount ?? 1 }, 1fr)`;
		// --- Mobile Logic (Fallback order: tablet -> desktop -> default) ---
		let mobileValue = defaultPlaceholder;

		// Prioritize desktop template if it exists
		if ( ! isEmpty( columns.desktop ) ) {
			mobileValue = columns.desktop;
		}
		// Overwrite with tablet template if it exists (higher priority)
		if ( ! isEmpty( columns.tablet ) ) {
			mobileValue = columns.tablet;
		}

		return {
			mobile: mobileValue,
			tablet: ! isEmpty( columns.desktop )
				? columns.desktop
				: defaultPlaceholder,
			desktop: defaultPlaceholder,
		};
	}, [ columns, columnCount ] );
};

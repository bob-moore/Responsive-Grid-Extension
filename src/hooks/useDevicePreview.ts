/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';

/**
 * Type definition for the editor store.
 * Ensures TypeScript knows that `getDeviceType` is a valid function.
 */
interface EditorStore {
	getDeviceType: () => 'Desktop' | 'Tablet' | 'Mobile' | undefined;
}

/**
 * Custom hook to get the current device preview type from the WordPress editor.
 * Defaults to 'desktop' if no device type is returned.
 *
 * @return {'desktop' | 'tablet' | 'mobile'} The current device type in lowercase.
 */
export const useDevicePreview = (): 'desktop' | 'tablet' | 'mobile' => {
	const currentDevice = useSelect(
		( select ): 'desktop' | 'tablet' | 'mobile' => {
			const deviceType = select( 'core/editor' ) as EditorStore;
			return (
				( deviceType.getDeviceType()?.toLowerCase() as
					| 'desktop'
					| 'tablet'
					| 'mobile' ) || 'desktop'
			);
		},
		[]
	);

	return currentDevice;
};

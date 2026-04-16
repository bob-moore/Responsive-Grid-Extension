/**
 * WordPress dependencies
 */
import { useDispatch } from '@wordpress/data';
import { ButtonGroup, Button, Icon } from '@wordpress/components';
import { desktop, tablet, mobile } from '@wordpress/icons';
/**
 * Internal dependencies
 */
import style from './DeviceSwitcher.module.scss';
import { useDevicePreview } from '../hooks/useDevicePreview';

const breakpoints = [ 'desktop', 'tablet', 'mobile' ];

export const DeviceSwitcher = () => {
	const currentDevice = useDevicePreview();

	const siteDistpatcher =
		useDispatch( 'core/edit-site' )?.__experimentalSetPreviewDeviceType;
	const postDispatcher =
		useDispatch( 'core/edit-post' )?.__experimentalSetPreviewDeviceType;

	const setPreviewDeviceType = ( device: string ) => {
		if ( siteDistpatcher ) {
			siteDistpatcher( device );
		} else if ( postDispatcher ) {
			postDispatcher( device );
		}
	};

	const getIcon = ( device: string ) => {
		switch ( device ) {
			case 'mobile':
				return mobile;
			case 'tablet':
				return tablet;
			default:
				return desktop;
		}
	};

	const capitalizeFirstLetter = ( str: string ) =>
		str.charAt( 0 ).toUpperCase() + str.slice( 1 );

	return (
		<ButtonGroup className={ style.switcher }>
			{ breakpoints.map( ( device ) => (
				<Button
					key={ device }
					isPressed={ currentDevice === device }
					onClick={ () =>
						setPreviewDeviceType( capitalizeFirstLetter( device ) )
					}
				>
					<Icon icon={ getIcon( device ) } />
				</Button>
			) ) }
		</ButtonGroup>
	);
};

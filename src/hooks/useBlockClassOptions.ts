import { useState, useEffect, useMemo } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { applyFilters } from '@wordpress/hooks';

// Define the shape of an option
interface Option {
	label: string;
	value: string;
}

// Shared cache & fetch promise
let cachedBlockOptions: Record< string, Option[] > | null = null;
let fetchPromise: Promise< Record< string, Option[] > > | null = null;

// Custom hook
export const useBlockClassOptions = ( blockName: string ): Option[] => {
	const [ options, setOptions ] = useState< Option[] >( [] );

	const filterOptions = useMemo( () => {
		return ( data: Option[] ) => {
			return applyFilters(
				'mwf-cornerstone.blockPresets.classOptions',
				data,
				blockName
			) as Option[];
		};
	}, [ blockName ] );

	// const f = useMemo( () => filterOptions, [] ); // eslint-disable-line react-hooks/exhaustive-deps, react-hooks/rules-of-hooks()

	useEffect( () => {
		if ( cachedBlockOptions ) {
			setOptions(
				filterOptions( [
					...( cachedBlockOptions.global || [] ),
					...( cachedBlockOptions[ blockName ] || [] ),
				] )
			);
			return;
		}

		if ( ! fetchPromise ) {
			fetchPromise = apiFetch( {
				path: '/block-preset-classes/v2/all',
				method: 'GET',
			} )
				.then( ( response: Record< string, Option[] > ) => {
					cachedBlockOptions = response;
					return response;
				} )
				// eslint-disable-next-line @typescript-eslint/no-unused-vars -- keep for debugging
				.catch( ( error ) => {
					return {};
				} );
		}

		fetchPromise.then( ( response ) => {
			setOptions(
				filterOptions( [
					...( response.global || [] ),
					...( response[ blockName ] || [] ),
				] )
			);
		} );
	}, [ blockName, filterOptions ] );

	return options;
};

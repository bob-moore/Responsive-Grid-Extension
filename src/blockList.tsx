import { createHigherOrderComponent } from '@wordpress/compose';

import isEmpty from './utilities/isEmpty';

import type { BlockAttributes, BlockTypeObject } from './types';

type WrapperProps = {
	style?: { [ key: string ]: any };
	className?: string;
};

type BlockListProps = BlockTypeObject & {
	block: BlockTypeObject;
	attributes: BlockAttributes;
	wrapperProps: WrapperProps;
};

export const BlockList = createHigherOrderComponent( ( BlockListBlock ) => {
	return ( props: BlockListProps ) => {
		const { attributes, name } = props;

		if ( 'core/group' !== name ) {
			return <BlockListBlock { ...props } />;
		}

		const { responsiveGridColumns, responsiveGridRows } = attributes;

		/**
		 * Check if any custom columns or rows are set
		 */
		const hasCustomColumns = ! Object.values( responsiveGridColumns ).every(
			( v ) =>
			isEmpty( v )
		);
		const hasCustomRows = ! Object.values( responsiveGridRows ).every(
			( v ) =>
			isEmpty( v )
		);
		/**
		 * Bail out if no custom columns or rows are set
		 */
		if ( ! hasCustomColumns && ! hasCustomRows ) {
			return <BlockListBlock { ...props } />;
		}

		const styles = new Map< string, string >();

		const classNames = new Set< string >();

		if ( hasCustomColumns ) {
			classNames.add( 'has-responsive-grid-columns' );
			Object.entries( responsiveGridColumns ).forEach(
				( [ device, value ] ) => {
				if ( ! isEmpty( value ) ) {
					classNames.add( `has-responsive-grid-columns--${ device }` );
					styles.set(
						`--responsive-grid-template-columns--${ device }`,
						value
					);
				}
				}
			);
		}

		if ( hasCustomRows ) {
			classNames.add( 'has-responsive-grid-rows' );

			Object.entries( responsiveGridRows ).forEach(
				( [ device, value ] ) => {
				if ( ! isEmpty( value ) ) {
					classNames.add( `has-responsive-grid-rows--${ device }` );
					styles.set(
						`--responsive-grid-template-rows--${ device }`,
						value
					);
				}
				}
			);
		}

		const wrapperProps: WrapperProps = {
			...props?.wrapperProps,
			className: [
				props?.wrapperProps?.className,
				...Array.from( classNames ),
			]
				.filter( Boolean )
				.join( ' ' ),
			style: {
				...props?.wrapperProps?.style,
				...Object.fromEntries( styles ),
			},
		};

		return <BlockListBlock { ...props } wrapperProps={ wrapperProps } />;
	};
}, 'BlockList' );

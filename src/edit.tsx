/**
 * WordPress Dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';

/**
 * External Dependencies
 */
import React, { FC } from 'react';

/**
 * Internal Dependencies
 */
import type { BlockEditProps } from './types';
import type { BlockAttributes } from './types';
import { DeviceSwitcher } from './components/DeviceSwitcher';
import { useColumnPlaceholder, useRowPlaceholder, useDevicePreview } from './hooks';

type GroupBlockEditProps = BlockEditProps< BlockAttributes >;
type GroupBlockEditComponent = FC< GroupBlockEditProps >;
type WrappedGroupBlockEditComponent =
	React.ComponentType< GroupBlockEditProps >;

/**
 * Higher-Order Component (HOC) to add a custom "Classes & Presets" panel in the block sidebar.
 * This panel allows users to manage custom class names through a multi-select input.
 */
export const Edit = createHigherOrderComponent<
	GroupBlockEditComponent,
	WrappedGroupBlockEditComponent
>( ( BlockEdit ) => {
	return ( props: BlockEditProps< BlockAttributes > ) => {
		const { attributes, setAttributes, isSelected, name } = props;

		if ( 'core/group' !== name ) {
			return <BlockEdit { ...props } />;
		}

		const { layout, responsiveGridColumns, responsiveGridRows } = attributes;

		const { columnCount = null, type } = layout;

		if ( type !== 'grid' || ! columnCount ) {
			return <BlockEdit { ...props } />;
		}

		const cPlaceholder = useColumnPlaceholder(
			responsiveGridColumns,
			columnCount
		);
		const rPlaceholder = useRowPlaceholder( responsiveGridRows );
		const currentDevice = useDevicePreview();

		const setCustomTemplate = (
			templateKey: 'responsiveGridColumns' | 'responsiveGridRows',
			newValue: string
		) => {
			const currentTemplates = attributes[ templateKey ];

			const updatedTemplates = {
				...currentTemplates,
				[ currentDevice ]: newValue,
			};

			setAttributes( {
				[ templateKey ]: updatedTemplates,
			} );
		};

		return (
			<>
				{ /* Render the block editor as usual */ }
				<BlockEdit { ...props } />
				{ isSelected && (
					<InspectorControls group="default">
						<PanelBody title={ 'Custom Layout' }>
							<DeviceSwitcher />
							<TextControl
								__nextHasNoMarginBottom
								__next40pxDefaultSize
								label="Columns"
								placeholder={ cPlaceholder[ currentDevice ] }
								value={ responsiveGridColumns[ currentDevice ] }
								onChange={ ( newValue ) =>
									setCustomTemplate(
										'responsiveGridColumns',
										newValue
									)
								}
							/>
							<TextControl
								__nextHasNoMarginBottom
								__next40pxDefaultSize
								label="Rows"
								placeholder={ rPlaceholder[ currentDevice ] }
								value={ responsiveGridRows[ currentDevice ] }
								onChange={ ( newValue ) =>
									setCustomTemplate(
										'responsiveGridRows',
										newValue
									)
								}
							/>
						</PanelBody>
					</InspectorControls>
				) }
			</>
		);
	};
}, 'Edit' );

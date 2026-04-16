import { addFilter } from '@wordpress/hooks';
import { Edit } from './edit';
import { BlockList } from './blockList';
import { addResponsiveGridAttributes } from './attributes';

addFilter(
	'blocks.registerBlockType',
	'bmd/grid-custom-attributes',
	addResponsiveGridAttributes
);

addFilter( 'editor.BlockEdit', 'bmd/with-grid-columns', Edit );

addFilter(
	'editor.BlockListBlock',
	'bmd/grid-columns-block-list',
	BlockList
);

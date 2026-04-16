/**
 * Block Edit Props Type Definition
 */
export type BlockEditProps<T = {}> = {
    attributes: T;
    setAttributes: (attrs: Partial<T>) => void;
    clientId: string;
    isSelected: boolean;
    context: Record<string, any>;
    name: string;
    insertBlocksAfter?: (blocks: any[]) => void;
    onReplace?: (blocks: any[]) => void;
    mergeBlocks?: (forward: boolean) => void;
    __unstableLayoutClassNames?: string;
    toggleSelection?: (value?: boolean) => void;
};

export type LayoutAttributes = {
    columnCount?: number;
    type: string;
    isManualPlacement?: boolean;
    responsiveGridColumns?: {
        desktop: string;
        tablet: string;
        mobile: string;
    }
    responsiveGridRows?: {
        desktop: string;
        tablet: string;
        mobile: string;
    }
}

export type ResponsiveGridTemplate = {
    desktop: string;
    tablet: string;
    mobile: string;
}

export type BlockAttributes<T = {}> = {
	className: string;
	clientId: string;
	name: string;
    layout: {
        columnCount?: number;
        type: string;
        isManualPlacement?: boolean;
    };
    responsiveGridColumns: ResponsiveGridTemplate;
    responsiveGridRows: ResponsiveGridTemplate;
    style?: { [key: string]: any };
} & T;

export type BlockTypeObject = {
    name: string;
};

export type BlockProps = {
    className?: string;
    style?: { [key: string]: any };
}
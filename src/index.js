import edit from './es-edit';

const { __ } = wp.i18n;
const { registerBlockType,
} = wp.blocks;
const {
    RichText,
    InspectorControls,
    BlockIcon,
    MediaPlaceholder,
} = wp.editor;
const {
    TextControl,
    PanelBody,
    PanelRow,
    Button,
    ButtonGroup,
    IconButton,
    Placeholder
} = wp.components;
const el = wp.element.createElement;

const edusharing_icon = el('svg', { width: 20, height: 20 },
    el('polygon', { fill:'#3162A7', points: "2.748,19.771 0.027,15.06 2.748,10.348 8.188,10.348 10.908,15.06 8.188,19.771" } ),
    el('polygon', { fill:'#7F91C3', points: "11.776,14.54 9.056,9.829 11.776,5.117 17.218,5.117 19.938,9.829 17.218,14.54" } ),
    el('polygon', { fill:'#C1C6E3', points: "2.721,9.423 0,4.712 2.721,0 8.161,0 10.882,4.712 8.161,9.423" } )
);

registerBlockType( 'es/edusharing-block', {
    title: __( 'Edu-Sharing' ),
    icon: edusharing_icon,
    category: 'embed',
    supports: {
        align: true,
    },
    attributes: {
        repoDomain: {
            type: 'string',
            source: 'meta',
            meta: 'es_repo_domain',
        },
        repoTicket: {
            type: 'string',
            source: 'meta',
            meta: 'es_repo_ticket',
        },
        pluginURL: {
            type: 'string',
            source: 'meta',
            meta: 'es_plugin_url',
        },
        usage: {
            type: 'boolean',
            default: false
        },
        previewImg: {
            type: 'string',
        },
        previewUrl: {
            type: 'string',
        },
        nodeID: {
            type: 'string',
            default: ''
        },
        objectUrl: {
            type: 'string',
        },
        objectVersion: {
            type: 'string',
            default: ''
        },
        objectTitle: {
            type: 'string',
            default: ''
        },
        mimeType: {
            type: 'string',
        },
        mediaType: {
            type: 'string',
        },
        orgHeight: {
            type: 'integer',
        },
        orgWidth: {
            type: 'integer',
        },
        objectHeight: {
            type: 'integer',
        },
        objectWidth: {
            type: 'integer',
        },
        objectAlign: {
            type: 'string',
        },
        objectCaption: {
            type: 'string',
        },
        resourceId: {
            type: 'integer',
        },
        hideObj: {
            type: 'string',
            default: 'none'
        },
        // Saved search properties.
        // Defaults are defined in es-edit.js as values that equal defaults defined here are not
        // sent to the render callback.
        maxItems: {
            type: 'integer',
        },
        sortBy: {
            type: 'string',
        },
        // view: {
        //     type: 'string',
        // }

    },

    edit,

    save() {
        return null
    },

} );


/**
 * WordPress dependencies
 */
import {
    Button,
    Icon,
    PanelBody,
    TextareaControl,
    TextControl,
    Placeholder,
    ButtonGroup,
    ResizableBox,
    SelectControl,
} from '@wordpress/components';

import {
    BlockIcon,
    InspectorControls,
} from '@wordpress/block-editor';

import classnames from 'classnames';
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { Component } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';

const el = wp.element.createElement;

const edusharing_icon = el('svg', { width: 20, height: 20 },
    el('polygon', { fill:'#3162A7', points: "2.748,19.771 0.027,15.06 2.748,10.348 8.188,10.348 10.908,15.06 8.188,19.771" } ),
    el('polygon', { fill:'#7F91C3', points: "11.776,14.54 9.056,9.829 11.776,5.117 17.218,5.117 19.938,9.829 17.218,14.54" } ),
    el('polygon', { fill:'#C1C6E3', points: "2.721,9.423 0,4.712 2.721,0 8.161,0 10.882,4.712 8.161,9.423" } )
);

class esEdit extends Component {

    constructor( { attributes } ) {
        super( ...arguments );
        this.toggleIsEditing = this.toggleIsEditing.bind( this );
        this.updateWidth = this.updateWidth.bind( this );
        this.updateHeight = this.updateHeight.bind( this );
        this.updateDimensions = this.updateDimensions.bind( this );

        this.state = {
            isEditing: ! attributes.previewImg,
            isFocus: ''
        };
    }

    componentDidMount() {
        const { attributes, setAttributes } = this.props;
        //for existing objects, switch to the preview url. this allows viewing of the object without permissions.
        if(attributes.previewUrl){
            setAttributes( { previewImg: attributes.previewUrl } );
        }
    }

    componentDidUpdate( prevProps ) {
    }

    componentWillUnmount() {
        const { attributes, setAttributes } = this.props;
        this.deleteUsage(attributes.objectUrl, attributes.resourceId); //deleteUsage
    }

    //toggles the placeholder
    toggleIsEditing() {
        this.setState( {
            isEditing: ! this.state.isEditing,
        } );
    }
    
    updateWidth( width ) {
        this.props.setAttributes( {
            objectWidth: parseInt( width, 10 )
        } );
    }

    updateHeight( height ) {
        this.props.setAttributes( {
            objectHeight: parseInt( height, 10 ),
        } );
    }

    updateDimensions( objectWidth = undefined, objectHeight = undefined ) {
        return () => {
            this.props.setAttributes( { objectWidth, objectHeight } );
        };
    }

    deleteUsage(objectUrl, resourceId){
        const { attributes, setAttributes } = this.props;
        const post_id = wp.data.select("core/editor").getCurrentPostId();
        const plugin_url = attributes.pluginURL + '/edusharing/';
        fetch(plugin_url + 'fetch.php', {
            method : 'post',
            mode:    'cors',
            headers: {
                'Content-Type': 'application/json',  // sent request
                'Accept':       'application/json'   // expected data sent back
            },
            body: JSON.stringify({
                useCase: 'deleteUsage',
                post_id: post_id,
                objectUrl: objectUrl,
                resourceId: resourceId
            })
        })
            .then(function(response) {
                if (response.status >= 200 && response.status < 300) {
                    return response.text()
                }
                throw new Error(response.statusText)
            })
            .then(function(response) {
                console.log(response);
            })
    }

    //open the repo & get data
    open_repo(repoTicket, repoDomain) {
        const { attributes, setAttributes } = this.props;

        if ( this.state.isEditing ) {
            this.toggleIsEditing();
        }

        //Window-Event-Listener gets the Objects data and sets the usage
        window.addEventListener('message', function handleRepo(event) {
            if (event.data.event == "APPLY_NODE") {
                const node = event.data.data;
                window.console.log(node);
                window.win.close();

                const post_id = wp.data.select("core/editor").getCurrentPostId();
                const post_title = wp.data.select("core/editor").getCurrentPost().title;
                const plugin_url = attributes.pluginURL + '/edusharing/';

                //if there is an old object delete it's usage
                if(attributes.objectUrl){
                    fetch(plugin_url + 'fetch.php', {
                        method : 'post',
                        mode:    'cors',
                        headers: {
                            'Content-Type': 'application/json',  // sent request
                            'Accept':       'application/json'   // expected data sent back
                        },
                        body: JSON.stringify({
                            useCase: 'deleteUsage',
                            post_id: post_id,
                            objectUrl: attributes.objectUrl,
                            resourceId: attributes.resourceId
                        })
                    })
                        .then(function(response) {
                            if (response.status >= 200 && response.status < 300) {
                                return response.text()
                            }
                            throw new Error(response.statusText)
                        })
                        .then(function(response) {
                            console.log(response);
                        })
                }

                let height;
                let width;
                const url = node.objectUrl;
                const version = node.properties['cclom:version'];
                const repoID = node.parent.repo;
                if(!node.properties["ccm:height"]){
                    height = '';
                    width = '';
                }else{
                    height = node.properties["ccm:height"][0];
                    width = node.properties["ccm:width"][0];
                }
                let title = node.title;
                if(!title){
                    title = node.properties["cm:name"];
                }
                //generate hopefully unique resourceID
                const resourceId = post_id.toString() + (Math.floor(Math.random() * 10000) + 1000);
                const previewUrl = plugin_url + 'preview.php?post_id=' + post_id + '&objectUrl=' + url + '&objectVersion=' + version + '&repoId=' + repoID + '&resourceId=' + resourceId;
                //set the attributes from the node object
                setAttributes({
                    previewImg: node.preview.url,
                    previewUrl: previewUrl,
                    nodeID: node.ref.id,
                    objectUrl: url,
                    objectVersion: node.properties['cclom:version'],
                    objectHeight: parseInt( height, 10 ),
                    objectWidth: parseInt( width, 10 ),
                    orgHeight: parseInt( height, 10 ),
                    orgWidth: parseInt( width, 10 ),
                    objectTitle: title.toString(),
                    objectCaption: node.description,
                    resourceId: parseInt(resourceId, 10),
                    mimeType: node.mimetype,
                    mediaType: node.mediatype,
                    hideObj: 'block', //toggles the close-button on the placeholder
                });

                //set new usage
                fetch(plugin_url + 'fetch.php', {
                    method : 'post',
                    mode:    'cors',
                    headers: {
                        'Content-Type': 'application/json',  // sent request
                        'Accept':       'application/json'   // expected data sent back
                    },
                    body: JSON.stringify({
                        useCase: 'setUsage',
                        post_id: post_id,
                        post_title: post_title,
                        objectUrl: url,
                        objectVersion: version,
                        resourceId: resourceId
                    })
                })
                    .then(function(response) {
                        if (response.status >= 200 && response.status < 300) {
                            return response.text()
                        }
                        throw new Error(response.statusText)
                    })
                    .then(function(response) {
                        console.log(response);
                    })
                //remove event listener so only this block updates
                window.removeEventListener('message', handleRepo, false );
            }
        }, false);

        let url = repoDomain + '/components/search?&applyDirectories=true&reurl=WINDOW&ticket=' + repoTicket;
        window.win = window.open(url);
    }

    render() {
        const {isEditing} = this.state;
        const {
            attributes,
            setAttributes,
            toggleSelection,
            isSelected,
            className,
        } = this.props;
        const {
            repoDomain,
            repoTicket,
            previewImg,
            objectTitle,
            objectWidth,
            objectHeight
        } = attributes;

        const currentWidth = objectWidth;
        const currentHeight = objectHeight;

        const es_placeholder = (
            <Placeholder
                className='es-placeholder'
                icon={ <BlockIcon icon={ edusharing_icon } /> }
                label="Edusharing"
            >
                <div className='es'>
                    <button className='close' style={{display: attributes.hideObj}} onClick={ this.toggleIsEditing }><Icon icon="no-alt" /></button>
                    <p className='es-placeholder'>{__('Öffne das Repository um ein Edusharing-Objekt einzufügen', 'edusharing')}</p>
                    <Button onClick={ () => {
                        this.open_repo(repoTicket, repoDomain)
                    }}>
                        {__('Öffne Repository', 'edusharing')}
                    </Button>
                </div>
            </Placeholder>
        )

        //show placeholder
        if ( isEditing || ! previewImg ) {
            return (
                <React.Fragment>
                    { es_placeholder }
                </React.Fragment>
            );
        }

        const classes = classnames( className, {
            'is-focused': isSelected,
        } );

        const getInspectorControls = (width, height) => (
            <InspectorControls>
                <div className='es'>
                <PanelBody title={ __( 'Edusharing Einstellungen', 'edusharing') }>
                    <TextControl
                        label={ __( 'Titel' ) }
                        value={ objectTitle }
                        onChange={ function(changes){
                            setAttributes({ objectTitle: changes });
                        } }
                    />
                    {/*only show resize options when object has a size*/}
                    { width && (
                        <div className="block-library-image__dimensions">
                            <p className="block-library-image__dimensions__row">
                                { __( 'Image Dimensions' ) }
                            </p>
                            <div className="block-library-image__dimensions__row">
                                <TextControl
                                    type="number"
                                    className="block-library-image__dimensions__width"
                                    label={ __( 'Width' ) }
                                    value={ objectWidth || width || '' }
                                    min={ 1 }
                                    onChange={ this.updateWidth }
                                />
                                <TextControl
                                    type="number"
                                    className="block-library-image__dimensions__height"
                                    label={ __( 'Height' ) }
                                    value={ objectHeight || height || '' }
                                    min={ 1 }
                                    onChange={ this.updateHeight }
                                />
                            </div>
                            <div className="block-library-image__dimensions__row">
                                <ButtonGroup aria-label={ __( 'Image Size' ) }>
                                    { [ 25, 50, 75, 100 ].map( ( scale ) => {
                                        const scaledWidth = Math.round( width * ( scale / 100 ) );
                                        const scaledHeight = Math.round( height * ( scale / 100 ) );
                                        return (
                                            <Button
                                                key={ scale }
                                                isSmall
                                                onClick={ this.updateDimensions( scaledWidth, scaledHeight ) }
                                            >
                                                { scale }%
                                            </Button>
                                        );
                                    } ) }
                                </ButtonGroup>
                                <Button
                                    isSmall
                                    onClick={ this.updateDimensions(width, height) }
                                >
                                    { __( 'Reset' ) }
                                </Button>
                            </div>
                        </div>
                    )}

                    <TextareaControl
                        label="Caption"
                        value={ attributes.objectCaption }
                        onChange={ function(changes){
                            setAttributes({ objectCaption: changes });
                        } }
                    />
                    <div>
                        <p className='es-placeholder'>{__('Edusharing-Objekt ändern:', 'edusharing')}</p>
                        <Button onClick={() => {
                            this.open_repo(repoTicket, repoDomain)
                        }}>
                            {__('Öffne Repository', 'edusharing')}
                        </Button>
                    </div>
                </PanelBody>
                </div>
            </InspectorControls>
        );

        const getSimpleInspectorControls = () => (
            <InspectorControls>
                <div className='es'>
                    <PanelBody title={ __( 'Edusharing Einstellungen', 'edusharing') }>
                        <TextControl
                            label={ __( 'Titel' ) }
                            value={ objectTitle }
                            onChange={ function(changes){
                                setAttributes({ objectTitle: changes });
                            } }
                        />
                        <TextareaControl
                            label="Caption"
                            value={ attributes.objectCaption }
                            onChange={ function(changes){
                                setAttributes({ objectCaption: changes });
                            } }
                        />
                        <TextControl
                            type="number"
                            className="block-library-image__dimensions__width"
                            label={ __( 'Width' ) }
                            value={ objectWidth || '' }
                            min={ 1 }
                            onChange={ this.updateWidth }
                        />
                        <div>
                            <p className='es-placeholder'>{__('Edusharing-Objekt ändern:', 'edusharing')}</p>
                            <Button onClick={() => {
                                this.open_repo(repoTicket, repoDomain)
                            }}>
                                {__('Öffne Repository', 'edusharing')}
                            </Button>
                        </div>
                    </PanelBody>
                </div>
            </InspectorControls>
        );

        const getSavedSearchInspectorControls = () => (
            <InspectorControls>
                <div className='es'>
                    <PanelBody title={__('Edusharing Einstellungen', 'edusharing')}>
                        <TextControl
                            label={__('Titel')}
                            value={objectTitle}
                            onChange={function (changes) {
                                setAttributes({ objectTitle: changes });
                            }}
                        />
                        <TextareaControl
                            label="Caption"
                            value={attributes.objectCaption}
                            onChange={function (changes) {
                                setAttributes({ objectCaption: changes });
                            }}
                        />
                        <TextControl
                            type="number"
                            label={__('Maximum number of results')}
                            value={attributes.maxItems}
                            min={1}
                            onChange={(newValue) => {
                                setAttributes({ maxItems: parseInt(newValue, 10) });
                            }}
                        />
                        <SelectControl
                            label={__('Sort by')}
                            value={attributes.sortBy}
                            options={
                                [
                                    { value: 'cm:modified', label: __('Most recently changed') },
                                    { value: 'score', label: __('Relevance') },
                                ]
                            }
                            onChange={(newValue) => {
                                setAttributes({ sortBy: newValue });
                            }}
                        />
                        {/*
                        // Currently only one view is implemented. Uncomment this, when different
                        // views become available.
                        <SelectControl
                            label={__('View')}
                            value={attributes.view}
                            options={
                                [
                                    { value: 'tiles', label: __('Tiles') },
                                    { value: 'list', label: __('List') },
                                ]
                            }
                            onChange={(newValue) => {
                                setAttributes({ view: newValue });
                            }}
                        /> */}
                        <div>
                            <p className='es-placeholder'>{__('Edusharing-Objekt ändern:', 'edusharing')}</p>
                            <Button onClick={() => {
                                this.open_repo(repoTicket, repoDomain)
                            }}>
                                {__('Öffne Repository', 'edusharing')}
                            </Button>
                        </div>
                    </PanelBody>
                </div>
            </InspectorControls>
        );

        if(attributes.mediaType == 'link'){
            return(
                <React.Fragment>
                    <div className={'eduObject'} style={{maxWidth: objectWidth}}>
                        { getSimpleInspectorControls() }
                        <div className={'esTitle'} onDoubleClick={ this.toggleIsEditing }>
                            <Icon className={'esIcon'} icon={edusharing_icon} />
                            <Icon icon="admin-links" />
                            <p>{attributes.objectTitle}</p>
                        </div>
                        <p>{attributes.objectCaption}</p>
                    </div>
                </React.Fragment>
            )
        }

        if(attributes.mediaType == 'file-pdf'){
            return(
                <React.Fragment>
                    <div className={'eduObject'}>
                        { getSimpleInspectorControls() }
                        <div className={'esTitle'} onDoubleClick={ this.toggleIsEditing }>
                            <Icon className={'esIcon'} icon={edusharing_icon} />
                            <Icon icon="media-document" />
                            <p>{attributes.objectTitle}</p>
                        </div>
                        <p>{attributes.objectCaption}</p>
                    </div>
                </React.Fragment>
            )
        }

        if((attributes.mimeType == 'directory' || attributes.mediaType == 'folder')){
            return (
                <div className={'eduObject'}>
                    { getSimpleInspectorControls() }
                    <div className={'folder esTitle'} onDoubleClick={ this.toggleIsEditing }>
                        <Icon className={'esIcon'} icon={edusharing_icon} />
                        {/* <Icon className={'folderIcon'} icon="arrow-right-alt2" /> */}
                        <Icon icon="portfolio" />
                        <p>{attributes.objectTitle}</p>
                    </div>
                    <p>{attributes.objectCaption}</p>

                </div>
            );
        }

        if (attributes.mediaType === 'saved_search') {
            // Set default values. This is done here since default values defined in
            // `registerBlockType` will not be sent to the render callback and defaults defined in
            // `register_block_type` will not be available here.
            const defaults = {
                maxItems: 5,
                sortBy: 'score',
                // view: 'tiles',
            };
            const newValues = {};
            for (const key in defaults) {
                if (attributes[key] === undefined) {
                    newValues[key] = defaults[key];
                }
            }
            setAttributes(newValues);
            return (
                <div className={'eduObject'}>
                    {getSavedSearchInspectorControls()}
                    <div className={'esTitle'} onDoubleClick={this.toggleIsEditing}>
                        <Icon className={'esIcon'} icon={edusharing_icon} />
                        <Icon icon="search" />
                        <p>{attributes.objectTitle}</p>
                    </div>
                    <p>{attributes.objectCaption}</p>
                </div>
            );
        }

        //normal return for resizable objects
        return (
            <div className={'eduObject'}>
                { getInspectorControls(attributes.orgWidth, attributes.orgHeight) }
                <figure className={ classes + ' wp-block-image'}>
                    <ResizableBox
                        size={ {
                            width: objectWidth,
                            height: objectHeight,
                        } }
                        minHeight="50"
                        minWidth="50"
                        maxWidth="1280"
                        enable={ {
                            top: false,
                            right: true,
                            bottom: true,
                            left: false,
                            topRight: false,
                            bottomRight: true,
                            bottomLeft: false,
                            topLeft: false,
                        } }
                        lockAspectRatio
                        onResizeStart={ () => {
                            toggleSelection( false );
                        } }
                        onResizeStop={ ( event, direction, elt, delta ) => {
                            setAttributes( {
                                objectWidth: parseInt( currentWidth + delta.width, 10 ),
                                objectHeight: parseInt( currentHeight + delta.height, 10 ),
                            } );
                            toggleSelection( true );
                        } }
                    >
                        <img src={ attributes.previewImg } height={objectHeight} width={objectWidth} onDoubleClick={ this.toggleIsEditing }/>
                        <div className={'esTitle'} onDoubleClick={ this.toggleIsEditing }>
                            <Icon className={'esIcon'} icon={edusharing_icon} />
                            {attributes.objectTitle}
                        </div>
                    </ResizableBox>
                    <p>{attributes.objectCaption}</p>
                </figure>
            </div>
        );
    }
}

export default compose( [
    withSelect( ( select, props ) => {
        const { getSettings } = select( 'core/block-editor' );
        const { repoDomain } = props.attributes;
        const { repoTicket } = props.attributes;
        return {
            repoDomain: repoDomain,
            repoTicket: repoTicket,
        };
    } ),
] )( esEdit );

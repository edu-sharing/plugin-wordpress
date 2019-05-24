
/**
 * WordPress dependencies
 */
import {
    Button,
    IconButton,
    Icon,
    PanelBody,
    TextareaControl,
    TextControl,
    Placeholder,
    ButtonGroup,
    ResizableBox,
} from '@wordpress/components';

import {
    BlockAlignmentToolbar,
    BlockControls,
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
        this.toggleFocus = this.toggleFocus.bind( this );
        this.updateAlignment = this.updateAlignment.bind( this );
        this.updateWidth = this.updateWidth.bind( this );
        this.updateHeight = this.updateHeight.bind( this );
        this.updateDimensions = this.updateDimensions.bind( this );
        //this.setUsage = this.setUsage.bind(this);
        //this.deleteUsage = this.deleteUsage.bind(this);

        this.state = {
            isEditing: ! attributes.previewImg,
            isFocus: ''
        };
    }

    componentDidMount() {
        const { attributes, setAttributes } = this.props;

        if(attributes.previewUrl){
            setAttributes( { previewImg: attributes.previewUrl } );
        }
    }

    componentDidUpdate( prevProps ) {

    }

    componentWillUnmount() {
        const { attributes, setAttributes } = this.props;
        //deleteUsage
        this.deleteUsage(attributes.objectUrl, attributes.resourceId);

    }

    toggleIsEditing() {
        this.setState( {
            isEditing: ! this.state.isEditing,
        } );
        if ( this.state.isEditing ) {
            //speak( __( 'You are now viewing the image in the image block.' ) );
        } else {
            //speak( __( 'You are now editing the image in the image block.' ) );
        }
    }

    toggleFocus() {
        this.setState( {
            isFocus: 'test',
        } );
        if ( this.state.isFocus ) {
            //speak( __( 'You are now viewing the image in the image block.' ) );
        } else {
            //speak( __( 'You are now editing the image in the image block.' ) );
        }
    }

    updateAlignment( nextAlign ) {
        this.props.setAttributes( { objectAlign: nextAlign } );
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

    setUsage(url, version, resourceId){
        const { attributes, setAttributes } = this.props;
        const post_id = wp.data.select("core/editor").getCurrentPostId();
        const post_title = wp.data.select("core/editor").getCurrentPost().title;
        const plugin_url = wp.data.select("core/editor").getPermalinkParts().prefix + 'wp-content/plugins/edusharing/';

        //call setUsage.php
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
    }

    deleteUsage(objectUrl, resourceId){
        const { attributes, setAttributes } = this.props;
        const post_id = wp.data.select("core/editor").getCurrentPostId();
        const plugin_url = wp.data.select("core/editor").getPermalinkParts().prefix + 'wp-content/plugins/edusharing/';
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
                const plugin_url = wp.data.select("core/editor").getPermalinkParts().prefix + 'wp-content/plugins/edusharing/';

                if(attributes.objectUrl && attributes.objectUrl !== node.objectUrl){
                    //delete usage from the old object
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
                const version = node.contentVersion;
                const repoID = node.parent.repo;
                if(!node.properties["ccm:height"]){
                    height = '160';
                    width = '120';
                }else{
                    height = node.properties["ccm:height"][0];
                    width = node.properties["ccm:width"][0];
                }
                const resourceId = Math.floor(Math.random() * 10000) + post_id;
                console.log('resourceId: '+resourceId);
                const previewUrl = plugin_url + 'preview.php?post_id=' + post_id + '&objectUrl=' + url + '&objectVersion=' + version + '&repoId=' + repoID + '&resourceId=' + resourceId;

                setAttributes({
                    previewImg: node.preview.url,
                    previewUrl: previewUrl,
                    nodeID: node.ref.id,
                    objectUrl: node.objectUrl,
                    objectVersion: node.contentVersion,
                    objectHeight: parseInt( height, 10 ),
                    objectWidth: parseInt( width, 10 ),
                    orgHeight: parseInt( height, 10 ),
                    orgWidth: parseInt( width, 10 ),
                    objectTitle: node.title,
                    resourceId: resourceId,
                    mimeType: node.mimetype,
                    mediaType: node.mediatype,
                    hideObj: 'block',
                });

                //call setUsage
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
            objectAlign,
            repoDomain,
            repoTicket,
            previewImg,
            objectTitle,
            objectWidth,
            objectHeight
        } = attributes;

        const currentWidth = objectWidth;
        const currentHeight = objectHeight;

        const controls = (
            <BlockControls>
                <BlockAlignmentToolbar
                    value={objectAlign}
                    onChange={this.updateAlignment}
                />
            </BlockControls>
        );

        const es_placeholder = (
            <Placeholder
                class='es-placeholder'
                icon={ <BlockIcon icon={ edusharing_icon } /> }
                label="Edusharing"
            >
                <div class="es">
                    <button class='close' style={{display: attributes.hideObj}} onClick={ this.toggleIsEditing }>X</button>
                    <p class='es-placeholder'>{__('Öffne das Repository um ein Edusharing-Objekt einzufügen', 'es-edusharing-block')}</p>
                    <Button onClick={ () => {
                        this.open_repo(repoTicket, repoDomain)
                    }}>
                        {__('Öffne Repository', 'es-edusharing-block')}
                    </Button>
                </div>
            </Placeholder>
        )

        //show placeholder
        if ( isEditing || ! previewImg ) {
            return (
                <React.Fragment>
                    { controls }
                    { es_placeholder }
                </React.Fragment>
            );
        }

        const classes = classnames( className, {
            'is-focused': isSelected,
        } );

        const getInspectorControls = (width, height) => (
            <InspectorControls>
                <div class='es'>
                <PanelBody title={ __( 'Edusharing Settings' ) }>
                    <TextControl
                        label={ __( 'Title' ) }
                        value={ objectTitle }
                        onChange={ function(changes){
                            setAttributes({ objectTitle: changes });
                        } }
                    />

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

                                        //const isCurrent = objectWidth === scaledWidth && objectHeight === scaledHeight;

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

                    <TextareaControl
                        label="Caption"
                        value={ attributes.objectCaption }
                        onChange={ function(changes){
                            setAttributes({ objectCaption: changes });
                        } }
                    />
                    <div>
                        <p className='es-placeholder'>{__('Edusharing-Objekt ändern:', 'es-edusharing-block')}</p>
                        <Button onClick={() => {
                            this.open_repo(repoTicket, repoDomain)
                        }}>
                            {__('Öffne Repository', 'es-edusharing-block')}
                        </Button>
                    </div>
                </PanelBody>
                </div>
            </InspectorControls>
        );

        if(attributes.mediaType == 'link'){
            return(
                <React.Fragment>
                    <div className={'eduObject'}>
                        { controls }
                        { getInspectorControls(attributes.orgWidth, attributes.orgHeight) }
                        <div className={'esLink'} onDoubleClick={ this.toggleIsEditing }>
                            <div className={'esTitle'}>
                                <Icon icon="admin-links" />
                                {attributes.objectTitle}
                            </div>
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
                        { controls }
                        { getInspectorControls(attributes.orgWidth, attributes.orgHeight) }
                        <div className={'esLink'} onDoubleClick={ this.toggleIsEditing }>
                            <div className={'esTitle'}>
                                <Icon icon="media-document" />
                                {attributes.objectTitle}
                            </div>
                        </div>
                        <p>{attributes.objectCaption}</p>
                    </div>
                </React.Fragment>
            )
        }

        return (

            <div className={'eduObject'}>
                { controls }
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
                        <div className={'esTitle'} onDoubleClick={ this.toggleIsEditing }>{attributes.objectTitle}</div>
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
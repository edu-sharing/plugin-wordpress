
/**
 * WordPress dependencies
 */
import {
    Button,
    PanelBody,
    TextareaControl,
    TextControl,
    Placeholder,
} from '@wordpress/components';

import {
    BlockAlignmentToolbar,
    BlockControls,
    BlockIcon,
    InspectorControls,
} from '@wordpress/block-editor';

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
        this.updateAlignment = this.updateAlignment.bind( this );
        this.setUsage = this.setUsage.bind(this);
        this.deleteUsage = this.deleteUsage.bind(this);

        this.state = {
            isEditing: ! attributes.previewImg,
        };
    }

    componentDidMount() {
        //const { attributes, setAttributes } = this.props;
    }

    componentDidUpdate( prevProps ) {

    }

    componentWillUnmount() {
        const { attributes, setAttributes } = this.props;

        //deleteUsage
        this.deleteUsage(attributes.objectUrl);

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

    updateAlignment( nextAlign ) {
        this.props.setAttributes( { objectAlign: nextAlign } );
    }

    setUsage(url, version){
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

    deleteUsage(objectUrl){
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
                objectUrl: objectUrl})
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
                            objectUrl: attributes.objectUrl})
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
                    height = '50px';
                    width = '250px';
                }else{
                    height = node.properties["ccm:height"][0];
                    width = node.properties["ccm:width"][0];
                }
                const previewUrl = plugin_url + 'preview.php?post_id=' + post_id + '&objectUrl=' + url + '&objectVersion=' + version + '&repoId=' + repoID;

                setAttributes({
                    previewImg: node.preview.url,
                    previewUrl: previewUrl,
                    nodeID: node.ref.id,
                    objectUrl: node.objectUrl,
                    objectVersion: node.contentVersion,
                    objectHeight: height,
                    objectWidth: width,
                    objectTitle: node.title,
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
                console.log('removed evenlistener');
            }
        }, false);

        let url = repoDomain + '/components/search?reurl=WINDOW&ticket=' + repoTicket;
        window.win = window.open(url);
    }

    render() {
        const {isEditing} = this.state;
        const {
            attributes,
            setAttributes,
        } = this.props;
        const {
            objectAlign,
            repoDomain,
            repoTicket,
            previewImg,
            objectTitle,
            objectWidth,
            objectHeight,
        } = attributes;
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

        //show placeolder
        if ( isEditing || ! previewImg ) {
            return (
                <React.Fragment>
                    { controls }
                    { es_placeholder }
                </React.Fragment>
            );
        }

        const getInspectorControls = () => (
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
                                    value={ objectWidth || '' }
                                    min={ 1 }
                                    onChange={ function(changes){
                                        setAttributes({ objectWidth: changes });
                                    } }
                                />
                                <TextControl
                                    type="number"
                                    className="block-library-image__dimensions__height"
                                    label={ __( 'Height' ) }
                                    value={ objectHeight || '' }
                                    min={ 1 }
                                    onChange={ function(changes){
                                        setAttributes({ objectHeight: changes });
                                    } }
                                />
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

        return (
            <div className={'eduObject'}>
                { controls }
                { getInspectorControls() }
                <figure class={attributes.objectAlign}>
                    <img src={ attributes.previewUrl } height={attributes.objectHeight} width={attributes.objectWidth} onDoubleClick={ this.toggleIsEditing }/>
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
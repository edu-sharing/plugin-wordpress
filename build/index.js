/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/assertThisInitialized.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/assertThisInitialized.js ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _assertThisInitialized(self) {
  if (self === void 0) {
    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  }

  return self;
}

module.exports = _assertThisInitialized;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/classCallCheck.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/classCallCheck.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

module.exports = _classCallCheck;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/createClass.js":
/*!************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/createClass.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  return Constructor;
}

module.exports = _createClass;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/getPrototypeOf.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/getPrototypeOf.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _getPrototypeOf(o) {
  module.exports = _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
    return o.__proto__ || Object.getPrototypeOf(o);
  };
  return _getPrototypeOf(o);
}

module.exports = _getPrototypeOf;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/inherits.js":
/*!*********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/inherits.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var setPrototypeOf = __webpack_require__(/*! ./setPrototypeOf */ "./node_modules/@babel/runtime/helpers/setPrototypeOf.js");

function _inherits(subClass, superClass) {
  if (typeof superClass !== "function" && superClass !== null) {
    throw new TypeError("Super expression must either be null or a function");
  }

  subClass.prototype = Object.create(superClass && superClass.prototype, {
    constructor: {
      value: subClass,
      writable: true,
      configurable: true
    }
  });
  if (superClass) setPrototypeOf(subClass, superClass);
}

module.exports = _inherits;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var _typeof = __webpack_require__(/*! ../helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js");

var assertThisInitialized = __webpack_require__(/*! ./assertThisInitialized */ "./node_modules/@babel/runtime/helpers/assertThisInitialized.js");

function _possibleConstructorReturn(self, call) {
  if (call && (_typeof(call) === "object" || typeof call === "function")) {
    return call;
  }

  return assertThisInitialized(self);
}

module.exports = _possibleConstructorReturn;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/setPrototypeOf.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/setPrototypeOf.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _setPrototypeOf(o, p) {
  module.exports = _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
    o.__proto__ = p;
    return o;
  };

  return _setPrototypeOf(o, p);
}

module.exports = _setPrototypeOf;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof2(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof2 = function _typeof2(obj) { return typeof obj; }; } else { _typeof2 = function _typeof2(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof2(obj); }

function _typeof(obj) {
  if (typeof Symbol === "function" && _typeof2(Symbol.iterator) === "symbol") {
    module.exports = _typeof = function _typeof(obj) {
      return _typeof2(obj);
    };
  } else {
    module.exports = _typeof = function _typeof(obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : _typeof2(obj);
    };
  }

  return _typeof(obj);
}

module.exports = _typeof;

/***/ }),

/***/ "./node_modules/classnames/index.js":
/*!******************************************!*\
  !*** ./node_modules/classnames/index.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
  Copyright (c) 2017 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames () {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg) && arg.length) {
				var inner = classNames.apply(null, arg);
				if (inner) {
					classes.push(inner);
				}
			} else if (argType === 'object') {
				for (var key in arg) {
					if (hasOwn.call(arg, key) && arg[key]) {
						classes.push(key);
					}
				}
			}
		}

		return classes.join(' ');
	}

	if (typeof module !== 'undefined' && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ "./src/es-edit.js":
/*!************************!*\
  !*** ./src/es-edit.js ***!
  \************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/createClass.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/inherits.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__);








/**
 * WordPress dependencies
 */







var el = wp.element.createElement;
var edusharing_icon = el('svg', {
  width: 20,
  height: 20
}, el('polygon', {
  fill: '#3162A7',
  points: "2.748,19.771 0.027,15.06 2.748,10.348 8.188,10.348 10.908,15.06 8.188,19.771"
}), el('polygon', {
  fill: '#7F91C3',
  points: "11.776,14.54 9.056,9.829 11.776,5.117 17.218,5.117 19.938,9.829 17.218,14.54"
}), el('polygon', {
  fill: '#C1C6E3',
  points: "2.721,9.423 0,4.712 2.721,0 8.161,0 10.882,4.712 8.161,9.423"
}));

var esEdit =
/*#__PURE__*/
function (_Component) {
  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_5___default()(esEdit, _Component);

  function esEdit(_ref) {
    var _this;

    var attributes = _ref.attributes;

    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, esEdit);

    _this = _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_2___default()(this, _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_3___default()(esEdit).apply(this, arguments));
    _this.toggleIsEditing = _this.toggleIsEditing.bind(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default()(_this));
    _this.updateWidth = _this.updateWidth.bind(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default()(_this));
    _this.updateHeight = _this.updateHeight.bind(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default()(_this));
    _this.updateDimensions = _this.updateDimensions.bind(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_4___default()(_this));
    _this.state = {
      isEditing: !attributes.previewImg,
      isFocus: ''
    };
    return _this;
  }

  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(esEdit, [{
    key: "componentDidMount",
    value: function componentDidMount() {
      var _this$props = this.props,
          attributes = _this$props.attributes,
          setAttributes = _this$props.setAttributes; //for existing objects, switch to the preview url. this allows viewing of the object without permissions.

      if (attributes.previewUrl) {
        setAttributes({
          previewImg: attributes.previewUrl
        });
      }
    }
  }, {
    key: "componentDidUpdate",
    value: function componentDidUpdate(prevProps) {}
  }, {
    key: "componentWillUnmount",
    value: function componentWillUnmount() {
      var _this$props2 = this.props,
          attributes = _this$props2.attributes,
          setAttributes = _this$props2.setAttributes;
      this.deleteUsage(attributes.objectUrl, attributes.resourceId); //deleteUsage
    } //toggles the placeholder

  }, {
    key: "toggleIsEditing",
    value: function toggleIsEditing() {
      this.setState({
        isEditing: !this.state.isEditing
      });
    }
  }, {
    key: "updateWidth",
    value: function updateWidth(width) {
      this.props.setAttributes({
        objectWidth: parseInt(width, 10)
      });
    }
  }, {
    key: "updateHeight",
    value: function updateHeight(height) {
      this.props.setAttributes({
        objectHeight: parseInt(height, 10)
      });
    }
  }, {
    key: "updateDimensions",
    value: function updateDimensions() {
      var _this2 = this;

      var objectWidth = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : undefined;
      var objectHeight = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : undefined;
      return function () {
        _this2.props.setAttributes({
          objectWidth: objectWidth,
          objectHeight: objectHeight
        });
      };
    }
  }, {
    key: "deleteUsage",
    value: function deleteUsage(objectUrl, resourceId) {
      var _this$props3 = this.props,
          attributes = _this$props3.attributes,
          setAttributes = _this$props3.setAttributes;
      var post_id = wp.data.select("core/editor").getCurrentPostId();
      var plugin_url = attributes.pluginURL + '/edusharing/';
      fetch(plugin_url + 'fetch.php', {
        method: 'post',
        mode: 'cors',
        headers: {
          'Content-Type': 'application/json',
          // sent request
          'Accept': 'application/json' // expected data sent back

        },
        body: JSON.stringify({
          useCase: 'deleteUsage',
          post_id: post_id,
          objectUrl: objectUrl,
          resourceId: resourceId
        })
      }).then(function (response) {
        if (response.status >= 200 && response.status < 300) {
          return response.text();
        }

        throw new Error(response.statusText);
      }).then(function (response) {
        console.log(response);
      });
    } //open the repo & get data

  }, {
    key: "open_repo",
    value: function open_repo(repoTicket, repoDomain) {
      var _this$props4 = this.props,
          attributes = _this$props4.attributes,
          setAttributes = _this$props4.setAttributes;

      if (this.state.isEditing) {
        this.toggleIsEditing();
      } //Window-Event-Listener gets the Objects data and sets the usage


      window.addEventListener('message', function handleRepo(event) {
        if (event.data.event == "APPLY_NODE") {
          var node = event.data.data;
          window.console.log(node);
          window.win.close();
          var post_id = wp.data.select("core/editor").getCurrentPostId();
          var post_title = wp.data.select("core/editor").getCurrentPost().title;
          var plugin_url = attributes.pluginURL + '/edusharing/'; //if there is an old object delete it's usage

          if (attributes.objectUrl) {
            fetch(plugin_url + 'fetch.php', {
              method: 'post',
              mode: 'cors',
              headers: {
                'Content-Type': 'application/json',
                // sent request
                'Accept': 'application/json' // expected data sent back

              },
              body: JSON.stringify({
                useCase: 'deleteUsage',
                post_id: post_id,
                objectUrl: attributes.objectUrl,
                resourceId: attributes.resourceId
              })
            }).then(function (response) {
              if (response.status >= 200 && response.status < 300) {
                return response.text();
              }

              throw new Error(response.statusText);
            }).then(function (response) {
              console.log(response);
            });
          }

          var height;
          var width;
          var _url = node.objectUrl;
          var version = node.properties['cclom:version'];
          var repoID = node.parent.repo;

          if (!node.properties["ccm:height"]) {
            height = '';
            width = '';
          } else {
            height = node.properties["ccm:height"][0];
            width = node.properties["ccm:width"][0];
          }

          var title = node.title;

          if (!title) {
            title = node.properties["cm:name"];
          } //generate hopefully unique resourceID


          var resourceId = post_id.toString() + (Math.floor(Math.random() * 10000) + 1000);
          var previewUrl = plugin_url + 'preview.php?post_id=' + post_id + '&objectUrl=' + _url + '&objectVersion=' + version + '&repoId=' + repoID + '&resourceId=' + resourceId; //set the attributes from the node object

          setAttributes({
            previewImg: node.preview.url,
            previewUrl: previewUrl,
            nodeID: node.ref.id,
            objectUrl: _url,
            objectVersion: node.properties['cclom:version'],
            objectHeight: parseInt(height, 10),
            objectWidth: parseInt(width, 10),
            orgHeight: parseInt(height, 10),
            orgWidth: parseInt(width, 10),
            objectTitle: title.toString(),
            objectCaption: node.description,
            resourceId: parseInt(resourceId, 10),
            mimeType: node.mimetype,
            mediaType: node.mediatype,
            hideObj: 'block' //toggles the close-button on the placeholder

          }); //set new usage

          fetch(plugin_url + 'fetch.php', {
            method: 'post',
            mode: 'cors',
            headers: {
              'Content-Type': 'application/json',
              // sent request
              'Accept': 'application/json' // expected data sent back

            },
            body: JSON.stringify({
              useCase: 'setUsage',
              post_id: post_id,
              post_title: post_title,
              objectUrl: _url,
              objectVersion: version,
              resourceId: resourceId
            })
          }).then(function (response) {
            if (response.status >= 200 && response.status < 300) {
              return response.text();
            }

            throw new Error(response.statusText);
          }).then(function (response) {
            console.log(response);
          }); //remove event listener so only this block updates

          window.removeEventListener('message', handleRepo, false);
        }
      }, false);
      var url = repoDomain + '/components/search?&applyDirectories=true&reurl=WINDOW&ticket=' + repoTicket;
      window.win = window.open(url);
    }
  }, {
    key: "render",
    value: function render() {
      var _this3 = this;

      var isEditing = this.state.isEditing;
      var _this$props5 = this.props,
          attributes = _this$props5.attributes,
          setAttributes = _this$props5.setAttributes,
          toggleSelection = _this$props5.toggleSelection,
          isSelected = _this$props5.isSelected,
          className = _this$props5.className;
      var repoDomain = attributes.repoDomain,
          repoTicket = attributes.repoTicket,
          previewImg = attributes.previewImg,
          objectTitle = attributes.objectTitle,
          objectWidth = attributes.objectWidth,
          objectHeight = attributes.objectHeight;
      var currentWidth = objectWidth;
      var currentHeight = objectHeight;
      var es_placeholder = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Placeholder"], {
        className: "es-placeholder",
        icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_8__["BlockIcon"], {
          icon: edusharing_icon
        }),
        label: "Edusharing"
      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
        className: "es"
      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("button", {
        className: "close",
        style: {
          display: attributes.hideObj
        },
        onClick: this.toggleIsEditing
      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Icon"], {
        icon: "no-alt"
      })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", {
        className: "es-placeholder"
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Öffne das Repository um ein Edusharing-Objekt einzufügen', 'edusharing')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Button"], {
        onClick: function onClick() {
          _this3.open_repo(repoTicket, repoDomain);
        }
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Öffne Repository', 'edusharing')))); //show placeholder

      if (isEditing || !previewImg) {
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(React.Fragment, null, es_placeholder);
      }

      var classes = classnames__WEBPACK_IMPORTED_MODULE_9___default()(className, {
        'is-focused': isSelected
      });

      var getInspectorControls = function getInspectorControls(width, height) {
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_8__["InspectorControls"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: "es"
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["PanelBody"], {
          title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Edusharing Einstellungen', 'edusharing')
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["TextControl"], {
          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Titel'),
          value: objectTitle,
          onChange: function onChange(changes) {
            setAttributes({
              objectTitle: changes
            });
          }
        }), width && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: "block-library-image__dimensions"
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", {
          className: "block-library-image__dimensions__row"
        }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Image Dimensions')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: "block-library-image__dimensions__row"
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["TextControl"], {
          type: "number",
          className: "block-library-image__dimensions__width",
          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Width'),
          value: objectWidth || width || '',
          min: 1,
          onChange: _this3.updateWidth
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["TextControl"], {
          type: "number",
          className: "block-library-image__dimensions__height",
          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Height'),
          value: objectHeight || height || '',
          min: 1,
          onChange: _this3.updateHeight
        })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: "block-library-image__dimensions__row"
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["ButtonGroup"], {
          "aria-label": Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Image Size')
        }, [25, 50, 75, 100].map(function (scale) {
          var scaledWidth = Math.round(width * (scale / 100));
          var scaledHeight = Math.round(height * (scale / 100));
          return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Button"], {
            key: scale,
            isSmall: true,
            onClick: _this3.updateDimensions(scaledWidth, scaledHeight)
          }, scale, "%");
        })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Button"], {
          isSmall: true,
          onClick: _this3.updateDimensions(width, height)
        }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Reset')))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["TextareaControl"], {
          label: "Caption",
          value: attributes.objectCaption,
          onChange: function onChange(changes) {
            setAttributes({
              objectCaption: changes
            });
          }
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", {
          className: "es-placeholder"
        }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Edusharing-Objekt ändern:', 'edusharing')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Button"], {
          onClick: function onClick() {
            _this3.open_repo(repoTicket, repoDomain);
          }
        }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Öffne Repository', 'edusharing'))))));
      };

      var getSimpleInspectorControls = function getSimpleInspectorControls() {
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_8__["InspectorControls"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: "es"
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["PanelBody"], {
          title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Edusharing Einstellungen', 'edusharing')
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["TextControl"], {
          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Titel'),
          value: objectTitle,
          onChange: function onChange(changes) {
            setAttributes({
              objectTitle: changes
            });
          }
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["TextareaControl"], {
          label: "Caption",
          value: attributes.objectCaption,
          onChange: function onChange(changes) {
            setAttributes({
              objectCaption: changes
            });
          }
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["TextControl"], {
          type: "number",
          className: "block-library-image__dimensions__width",
          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Width'),
          value: objectWidth || '',
          min: 1,
          onChange: _this3.updateWidth
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", {
          className: "es-placeholder"
        }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Edusharing-Objekt ändern:', 'edusharing')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Button"], {
          onClick: function onClick() {
            _this3.open_repo(repoTicket, repoDomain);
          }
        }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Öffne Repository', 'edusharing'))))));
      };

      var getSavedSearchInspectorControls = function getSavedSearchInspectorControls() {
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_8__["InspectorControls"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: "es"
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["PanelBody"], {
          title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Edusharing Einstellungen', 'edusharing')
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["TextControl"], {
          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Titel'),
          value: objectTitle,
          onChange: function onChange(changes) {
            setAttributes({
              objectTitle: changes
            });
          }
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["TextareaControl"], {
          label: "Caption",
          value: attributes.objectCaption,
          onChange: function onChange(changes) {
            setAttributes({
              objectCaption: changes
            });
          }
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["TextControl"], {
          type: "number",
          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Maximum number of results'),
          value: attributes.maxItems,
          min: 1,
          onChange: function onChange(newValue) {
            setAttributes({
              maxItems: parseInt(newValue, 10)
            });
          }
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["SelectControl"], {
          label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Sort by'),
          value: attributes.sortBy,
          options: [{
            value: 'cm:modified',
            label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Most recently changed')
          }, {
            value: 'score',
            label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Relevance')
          }],
          onChange: function onChange(newValue) {
            setAttributes({
              sortBy: newValue
            });
          }
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", {
          className: "es-placeholder"
        }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Edusharing-Objekt ändern:', 'edusharing')), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Button"], {
          onClick: function onClick() {
            _this3.open_repo(repoTicket, repoDomain);
          }
        }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])('Öffne Repository', 'edusharing'))))));
      };

      if (attributes.mediaType == 'link') {
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(React.Fragment, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: 'eduObject',
          style: {
            maxWidth: objectWidth
          }
        }, getSimpleInspectorControls(), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: 'esTitle',
          onDoubleClick: this.toggleIsEditing
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Icon"], {
          className: 'esIcon',
          icon: edusharing_icon
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Icon"], {
          icon: "admin-links"
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", null, attributes.objectTitle)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", null, attributes.objectCaption)));
      }

      if (attributes.mediaType == 'file-pdf') {
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(React.Fragment, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: 'eduObject'
        }, getSimpleInspectorControls(), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: 'esTitle',
          onDoubleClick: this.toggleIsEditing
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Icon"], {
          className: 'esIcon',
          icon: edusharing_icon
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Icon"], {
          icon: "media-document"
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", null, attributes.objectTitle)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", null, attributes.objectCaption)));
      }

      if (attributes.mimeType == 'directory' || attributes.mediaType == 'folder') {
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: 'eduObject'
        }, getSimpleInspectorControls(), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: 'folder esTitle',
          onDoubleClick: this.toggleIsEditing
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Icon"], {
          className: 'esIcon',
          icon: edusharing_icon
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Icon"], {
          icon: "portfolio"
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", null, attributes.objectTitle)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", null, attributes.objectCaption));
      }

      if (attributes.mediaType === 'saved_search') {
        // Set default values. This is done here since default values defined in
        // `registerBlockType` will not be sent to the render callback and defaults defined in
        // `register_block_type` will not be available here.
        var defaults = {
          maxItems: 5,
          sortBy: 'score' // view: 'tiles',

        };
        var newValues = {};

        for (var key in defaults) {
          if (attributes[key] === undefined) {
            newValues[key] = defaults[key];
          }
        }

        setAttributes(newValues);
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: 'eduObject'
        }, getSavedSearchInspectorControls(), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
          className: 'esTitle',
          onDoubleClick: this.toggleIsEditing
        }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Icon"], {
          className: 'esIcon',
          icon: edusharing_icon
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Icon"], {
          icon: "search"
        }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", null, attributes.objectTitle)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", null, attributes.objectCaption));
      } //normal return for resizable objects


      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
        className: 'eduObject'
      }, getInspectorControls(attributes.orgWidth, attributes.orgHeight), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("figure", {
        className: classes + ' wp-block-image'
      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["ResizableBox"], {
        size: {
          width: objectWidth,
          height: objectHeight
        },
        minHeight: "50",
        minWidth: "50",
        maxWidth: "1280",
        enable: {
          top: false,
          right: true,
          bottom: true,
          left: false,
          topRight: false,
          bottomRight: true,
          bottomLeft: false,
          topLeft: false
        },
        lockAspectRatio: true,
        onResizeStart: function onResizeStart() {
          toggleSelection(false);
        },
        onResizeStop: function onResizeStop(event, direction, elt, delta) {
          setAttributes({
            objectWidth: parseInt(currentWidth + delta.width, 10),
            objectHeight: parseInt(currentHeight + delta.height, 10)
          });
          toggleSelection(true);
        }
      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("img", {
        src: attributes.previewImg,
        height: objectHeight,
        width: objectWidth,
        onDoubleClick: this.toggleIsEditing
      }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("div", {
        className: 'esTitle',
        onDoubleClick: this.toggleIsEditing
      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_7__["Icon"], {
        className: 'esIcon',
        icon: edusharing_icon
      }), attributes.objectTitle)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["createElement"])("p", null, attributes.objectCaption)));
    }
  }]);

  return esEdit;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_6__["Component"]);

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_10__["compose"])([Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_11__["withSelect"])(function (select, props) {
  var _select = select('core/block-editor'),
      getSettings = _select.getSettings;

  var repoDomain = props.attributes.repoDomain;
  var repoTicket = props.attributes.repoTicket;
  return {
    repoDomain: repoDomain,
    repoTicket: repoTicket
  };
})])(esEdit));

/***/ }),

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _es_edit__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./es-edit */ "./src/es-edit.js");

var __ = wp.i18n.__;
var registerBlockType = wp.blocks.registerBlockType;
var _wp$editor = wp.editor,
    RichText = _wp$editor.RichText,
    InspectorControls = _wp$editor.InspectorControls,
    BlockIcon = _wp$editor.BlockIcon,
    MediaPlaceholder = _wp$editor.MediaPlaceholder;
var _wp$components = wp.components,
    TextControl = _wp$components.TextControl,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow,
    Button = _wp$components.Button,
    ButtonGroup = _wp$components.ButtonGroup,
    IconButton = _wp$components.IconButton,
    Placeholder = _wp$components.Placeholder;
var el = wp.element.createElement;
var edusharing_icon = el('svg', {
  width: 20,
  height: 20
}, el('polygon', {
  fill: '#3162A7',
  points: "2.748,19.771 0.027,15.06 2.748,10.348 8.188,10.348 10.908,15.06 8.188,19.771"
}), el('polygon', {
  fill: '#7F91C3',
  points: "11.776,14.54 9.056,9.829 11.776,5.117 17.218,5.117 19.938,9.829 17.218,14.54"
}), el('polygon', {
  fill: '#C1C6E3',
  points: "2.721,9.423 0,4.712 2.721,0 8.161,0 10.882,4.712 8.161,9.423"
}));
registerBlockType('es/edusharing-block', {
  title: __('Edu-Sharing'),
  icon: edusharing_icon,
  category: 'embed',
  supports: {
    align: true
  },
  attributes: {
    repoDomain: {
      type: 'string',
      source: 'meta',
      meta: 'es_repo_domain'
    },
    repoTicket: {
      type: 'string',
      source: 'meta',
      meta: 'es_repo_ticket'
    },
    pluginURL: {
      type: 'string',
      source: 'meta',
      meta: 'es_plugin_url'
    },
    usage: {
      type: 'boolean',
      default: false
    },
    previewImg: {
      type: 'string'
    },
    previewUrl: {
      type: 'string'
    },
    nodeID: {
      type: 'string',
      default: ''
    },
    objectUrl: {
      type: 'string'
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
      type: 'string'
    },
    mediaType: {
      type: 'string'
    },
    orgHeight: {
      type: 'integer'
    },
    orgWidth: {
      type: 'integer'
    },
    objectHeight: {
      type: 'integer'
    },
    objectWidth: {
      type: 'integer'
    },
    objectAlign: {
      type: 'string'
    },
    objectCaption: {
      type: 'string'
    },
    resourceId: {
      type: 'integer'
    },
    hideObj: {
      type: 'string',
      default: 'none'
    },
    // Saved search properties.
    // Defaults are defined in es-edit.js as values that equal defaults defined here are not
    // sent to the render callback.
    maxItems: {
      type: 'integer'
    },
    sortBy: {
      type: 'string'
    } // view: {
    //     type: 'string',
    // }

  },
  edit: _es_edit__WEBPACK_IMPORTED_MODULE_0__["default"],
  save: function save() {
    return null;
  }
});

/***/ }),

/***/ "@wordpress/block-editor":
/*!**********************************************!*\
  !*** external {"this":["wp","blockEditor"]} ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["blockEditor"]; }());

/***/ }),

/***/ "@wordpress/components":
/*!*********************************************!*\
  !*** external {"this":["wp","components"]} ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["components"]; }());

/***/ }),

/***/ "@wordpress/compose":
/*!******************************************!*\
  !*** external {"this":["wp","compose"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["compose"]; }());

/***/ }),

/***/ "@wordpress/data":
/*!***************************************!*\
  !*** external {"this":["wp","data"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["data"]; }());

/***/ }),

/***/ "@wordpress/element":
/*!******************************************!*\
  !*** external {"this":["wp","element"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["element"]; }());

/***/ }),

/***/ "@wordpress/i18n":
/*!***************************************!*\
  !*** external {"this":["wp","i18n"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["i18n"]; }());

/***/ })

/******/ });
//# sourceMappingURL=index.js.map
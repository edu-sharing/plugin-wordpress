/**
 * @package    filter_edusharing
 * @copyright  metaVentis GmbH — http://metaventis.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


(function(jquery) {

    !function () {
        function a(a, b) {
            var c = void 0 !== window.pageYOffset ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop,
                d = document.documentElement.clientHeight, e = c + d;
            b = b || 0;
            var f = a.getBoundingClientRect();
            if (0 === f.height) return !1;
            var g = f.top + c - b, h = f.bottom + c + b;
            return h > c && e > g
        }

        jquery.expr[":"]["near-viewport"] = function (b, c, d) {
            var e = parseInt(d[3]) || 0;
            return a(b, e)
        }
    }();

    jquery.ajaxSetup({cache: false});



    var videoFormat = 'webm';
    var v = document.createElement('video');
    if (v.canPlayType && v.canPlayType('video/mp4').replace(/no/, '')) {
        videoFormat = 'mp4';
    }

    function renderEsObject(esObject, wrapper) {
        var url = esObject.attr("data-url") + '&videoFormat=' + videoFormat;
        if (typeof wrapper == 'undefined')
            var wrapper = esObject.parent();
        //alert(url);
        jquery.get(url, function (data) {
            wrapper.html('').append(data).css({ width: 'auto', height: 'auto'});
            if (data.toLowerCase().indexOf('data-view="lock"') >= 0)
                setTimeout(function () {
                    renderEsObject(esObject, wrapper);
                }, 1111);
        });
        esObject.removeAttr("data-type");
    }



    jquery(window).scroll(function () {
        jquery("div[data-type='esObject']:near-viewport(400)").each(function () {
            renderEsObject(jquery(this));
        })
    });

    jquery(document).ready(function() {

        jquery("div[data-type='esObject']:near-viewport(400)").each(function () {
            renderEsObject(jquery(this));
        })

        jquery("body").click(function (e) {

            if (jquery(e.target).closest(".edusharing_metadata").length) {
                //clicked inside ".edusharing_metadata" - do nothing
            } else if (jquery(e.target).closest(".edusharing_metadata_toggle_button").length) {
                jquery(".edusharing_metadata").hide();
                toggle_button = jquery(e.target);
                metadata = toggle_button.parent().find(".edusharing_metadata");
                if (metadata.hasClass('open')) {
                    metadata.toggleClass('open');
                    metadata.hide();
                } else {
                    jquery(".edusharing_metadata").removeClass('open');
                    metadata.toggleClass('open');
                    metadata.show();
                }
            } else {
                jquery(".edusharing_metadata").hide();
                jquery(".edusharing_metadata").removeClass('open');
            }
        })
    });

    })( jQuery );
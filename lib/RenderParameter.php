<?php

/**
 * Gnereates XML from array
 *
 * @package    mod_edusharing
 * @copyright  metaVentis GmbH — http://metaventis.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Gnereates XML from array
 *
 * @copyright  metaVentis GmbH — http://metaventis.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_edusharing_render_parameter {

    /**
     * @var array
     */
    private $dataarray;

    /**
     * Set dataarray to empty array
     */
    public function __construct() {
        $this->dataarray = array();
    }

    /**
     * Set dataarray and call mod_edusharing_make_xml()
     * @param array $pdataarray
     */
    public function edusharing_get_xml($pdataarray) {
        $this->dataarray = $pdataarray;
        return $this->edusharing_make_xml();
    }

    /**
     * Generate XML from dataarray
     * @return string
     */
    protected function edusharing_make_xml() {
        $dom = new DOMDocument('1.0');
        $root = $dom->createElement($this->dataarray[0], '');
        $dom->appendChild($root);

        foreach ($this->dataarray[1] as $key => $value) {
            $tmp = $dom->createElement($key, '');
            $tmpnode = $root->appendChild($tmp);

            foreach ($value as $key2 => $value2) {
                $tmp2 = $dom->createElement($key2, $value2);
                $tmpnode->appendChild($tmp2);
            }
        }

        return $dom->saveXML();
    }
}

<?php
// @codeCoverageIgnoreStart
defined('BASEPATH') || exit('No direct script access allowed');
// @codeCoverageIgnoreEnd

/**
 * Codeigniter PHP framework library class for dealing with gettext.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Language
 * @author      Joël Gaujard <joel@depiltech.com>
 * @author      Marko Martinović <marko@techytalk.info>
 * @link        https://github.com/joel-depiltech/codeigniter-gettext
 */
class Gettext
{
    /**
     * Initialize Codeigniter PHP framework and get configuration
     *
     * @codeCoverageIgnore
     * @param array $config Override default configuration
     */
    public function __construct($config = array())
    {
        log_message('info', 'Dummy Gettext Library Class Initialized');
    }

    public function is_initialized() {
        return true;
    }
}

/* End of file Gettext.php */
/* Location: ./libraries/config/gettext.php */
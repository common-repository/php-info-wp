<?php
if (!defined("ABSPATH")) exit;

class PHPInfoer
{
    public function info()
    {
        ob_start();
        phpinfo(INFO_ALL & ~INFO_LICENSE & ~INFO_CREDITS);
        $info = ob_get_clean();

        /**
         * Extract contents within <body> and </body> tags only
         */
        $info = preg_replace("/^.*?\<body\>/is", "", $info);
        $info = preg_replace("/<\/body\>.*?$/is", "", $info);

        $root = dirname(__FILE__);
        echo file_get_contents($root . "/header.html");
        
        /**
         * @todo WP Enqueue Style not working
         */
        echo "<style>", file_get_contents($root . "/phpinfo.css"), "</style>";
        
        echo $info;
        echo file_get_contents($root . "/footer.html");
    }

    public function admin_menus()
    {
        add_menu_page("PHP Info", "PHP Info (WP)", "manage_options", "PHPInfoer", array($this, "info"), "dashicons-welcome-widgets-menus", 70);
    }

    public function enqueue()
    {
        wp_enqueue_style("php-info-wp", plugins_url("php-info-wp/phpinfo.css"), array(), false, "all");
    }

    /**
     * Add project source code link
     *
     * @param array $links
     * @param string $file
     * @return array
     */
    public function row_meta($links = array(), $file = "")
    {
        if (strpos($file, "php-info-wp/phpinfo.php") !== false) {
            $new_links = array(
                "github" => '<a href="https://github.com/anytizer/phpinfo.wp" target="_blank">Project Source</a>',
            );

            $links = array_merge($links, $new_links);
        }

        return $links;
    }
}

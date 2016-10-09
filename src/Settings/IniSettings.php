<?php
namespace jdeathe\PhpHelloWorld\Settings;

require_once 'Settings/SettingsInterface.php';

class IniSettings implements SettingsInterface {

    /**
     * @var array|false
     */
    protected $settings = array();

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @param string $path Path to settings file (absolute or relative to include_path).
     */
    public function __construct($path)
    {
        if (!is_string($path) || mb_strpos($path, DIRECTORY_SEPARATOR) === false) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid file path provided: %s',
                    $path
                )
            );
        }

        $this->path = $path;
    }

    /**
     * @param string $key Setting key.
     * @param string|null $default Value to return if $key is not found.
     */
    public function get($key, $default = null)
    {
        if (empty($this->settings)) {
            $this->parse();
        }

        if (array_key_exists($key, $this->settings)) {
            return $this->settings[$key];
        } else {
            return $default;
        }
    }

    /**
     * Return the settings array.
     * @return array
     */
    public function getAll()
    {
        if (empty($this->settings)) {
            $this->parse();
        }

        return $this->settings;
    }

    /**
     * Parse the settings file.
     * @return void
     */
    public function parse()
    {
      $this->settings = parse_ini_file(
          stream_resolve_include_path(
              $this->path
          ),
          false
      );
    }
}
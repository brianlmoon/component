<?php

namespace Moonspot\Component;

/**
 * Abstract class for making HTML components
 *
 * @author      Brian Moon <brian@moonspot.net>
 * @copyright   1997-Present Brian Moon
 * @package     Moonspot\Component
 */
abstract class ComponentAbstract {

    /**
     * id attribute for all components
     */
    public string $id;

    /**
     * class attribute for all components
     */
    public string $class;

    /**
     * Array of data attributes. e.g. data-foo="bar"
     */
    public array $data;

    /**
     * Holds the attribute names
     */
    protected array $attribute_names;

    /**
     * Determines if the assets have been loaded or not
     *
     * @var        array
     */
    protected static array $assets_loaded = [];

    /**
     * Helper static function for rendering a component
     *
     * @param      array  $settings    The settings
     * @param      array  $attributes  The attributes
     */
    public static function render(array $settings = [], array $attributes = []): void {
        $class     = get_called_class();
        $component = new $class($settings, $attributes);
        static::loadAssets();
        $component->setDefaults();
        $component->markup();
    }

    /**
     * Constructs a new instance.
     *
     * @param      array            $settings    The settings
     * @param      array            $attributes  The attributes
     *
     * @throws     \LogicException
     */
    public function __construct(array $settings = [], array $attributes = []) {
        foreach ($settings as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            } else {
                throw new \LogicException("Invalid setting $name", 1);
            }
        }
        foreach ($attributes as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            } else {
                throw new \LogicException("Invalid attribute $name", 2);
            }
        }

        // if the id is empty, generate one
        if (empty($this->id)) {
            $this->id = uniqid('auto-id-');
        }

        // if class is empty, set it to an empty string
        if (empty($this->class)) {
            $this->class = '';
        }

        // if data is empty, set it to an empty array
        if (empty($this->data)) {
            $this->data = [];
        }

        $this->attribute_names = array_keys(json_decode(json_encode($this), true));
    }

    /**
     * Called after the constructor to allow a child element to set or modify
     * settings or attributes.
     */
    public function setDefaults() {
        // noop
    }

    /**
     * Loads the css and scripts if not already loaded
     */
    public static function loadAssets() {
        $class = get_called_class();
        if (empty(self::$assets_loaded[$class])) {
            self::$assets_loaded[$class] = true;
            $class::css();
            $class::script();
        }
    }

    /**
     * Renders the markup for the component
     *
     * @return void
     */
    abstract function markup(): void;

    /**
     * Called once to load any CSS needed for the component
     */
    public static function css() {
        // noop
    }

    /**
     * Called once to load any scripts needed for the component
     */
    public static function script() {
        // noop
    }

    /**
     * Generates an attributes string for the public properties of the component
     *
     * @return     string
     */
    protected function attributes(): string {
        $attributes = '';
        foreach ($this->attribute_names as $name) {
            if ($name === 'data') {
                continue;
            }
            if (empty($this->$name) && strlen((string)$this->$name) === 0) {
                continue;
            }
            $attributes .= $this->attribute($name, $this->$name);
        }
        foreach ($this->data as $name => $value) {
            if (empty($value) && strlen((string)$value) === 0) {
                continue;
            }
            $attributes .= $this->attribute('data-' . $name, $value);
        }

        return trim($attributes);
    }

    /**
     * Generates an HTML attribute
     *
     * @param      string  $name   The name
     * @param      mixed   $value  The value
     *
     * @return     string
     */
    protected function attribute(string $name, mixed $value): string {
        if (is_bool($value)) {
            return "$name ";
        } else {
            return "$name=\"" . htmlspecialchars(trim($value)) . '" ';
        }
    }
}

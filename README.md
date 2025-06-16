# Moonspot\Component

A library for creating HTML components in PHP.

Creating consitent HTML components is important for good user UX. This library
aims to make creating those components easier. It is based on similar work of
mine for years on different projects.

## Example

### Text Input

```php

use Moonspot\Component\ComponentAbstract;

class TextInput extends ComponentAbstract {

    // Define the attributes for the component as public properties
    // id and class are defined in the parent class for all components.
    public string   $type      = 'text';
    public string   $name      = '';
    public bool     $required  = false;
    public int|null $minlength = null;
    public int|null $maxlength = null;
    public int|null $size      = null;

    // function where the markup is defined
    public function markup() {
        ?>
        <input <?=$this->attributes()?> />
        <?php
    }

    // An inline style or a link tag to a css file can be used here.
    // Either way, it will only be included once in the output.
    public static function css() {
        ?>
        <style>
            input[type=text] {
                font-size: 14px;
            }
        </style>
        <?php
    }
}

TextInput::render(attributes: [
    'id' => 'myinput1'
]);

TextInput::render(attributes: [
    'id' => 'myinput2'
]);
```
Output:
```html
        <style>
            input[type=text] {
                font-size: 14px;
            }
        </style>
        <input id="myinput1" type="text" />
        <input id="myinput2" type="text" />
```
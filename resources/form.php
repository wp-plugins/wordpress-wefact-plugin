<?php
class form {

	public static function open($action, $method = "POST", $attr = array()) {
		$attr['action'] = $action;
		$attr['method'] = $method;
		$attr['class'] = 'wefact_form';

		echo '<form '.self::attr($attr).'>';
	}

	public static function label($for, $label, $attr = array()) {
		$attr['for'] = 'wefact_'.$for;

		echo '<label '.self::attr($attr).'>'.$label.'</label>';
	}

	public static function textfield($name, $value, $attr = array()) {
		$attr['type'] = 'text';
		$attr['name'] = $name;
		$attr['value'] = $value;
		$attr['id'] = 'wefact_'.$name;

		echo '<input '.self::attr($attr).'>';
	}

	public static function select($name, $options, $selected = null, $attr = array()) {
		$attr['name'] = $name;
		$attr['id'] = 'wefact_'.$name;

		echo '<select '.self::attr($attr).'>';
		foreach ($options as $k => $v) {
			echo '<option value="'.$k.'" '.($k === $selected ? 'selected="selected"' : '').'>'.$v.'</option>';
		}
		echo '</select>';	
	}

	public static function checkbox($name, $value, $attr = array()) {
		$attr['type'] = 'checkbox';
		$attr['name'] = $name;
		$attr['value'] = $value;

		echo '<input '.self::attr($attr).'>';
	}

	public static function submit($value, $attr = array()) {
		$attr['type'] = 'submit';
		$attr['value'] = $value;
		$attr['class'] = 'button-primary';
		echo '<input '.self::attr($attr).'>';
	}

	public static function close() {
		echo '</form>';
	}

	private function attr($attr = array()) {
		if ( ! empty($attr)) {
			foreach ($attr as $k => $v) {
				$attributes[] = $k.'="'.$v.'"';
			}
			return implode(' ', $attributes);
		}
		else {
			return null;
		}
	}
}
?>
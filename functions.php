<?php
function debug($data) {
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}

function price($number) {
	if (empty($number)) $number = 0;
	return '&euro; '.number_format($number, 2, ',', '.');
}

function percentage($number) {
	return ($number * 100).'%';
}

function dmy($date) {
	$timestamp = strtotime($date);
	return date('d-m-Y', $timestamp);
}

function fulldate($date) {
	$timestamp = strtotime($date);
	$day = date('d', $timestamp);
	$month = date('m', $timestamp);
	$year = date('m', $timestamp);
}

function invoice_statuses($status = null) {
	$statuses = array(
		0 => __('Concept invoice', 'wp_wefact'),
		2 => __('Send', 'wp_wefact'),
		3 => __('Partly paid', 'wp_wefact'),
		4 => __('Paid', 'wp_wefact'),
		8 => __('Credit invoice', 'wp_wefact'),
		9 => __('Expired', 'wp_wefact'),
	);
	if (isset($status)) {
		return $statuses[$status];
	}
	else {
		return $statuses;
	}
}

function pricequote_statuses($status = null) {
	$statuses = array(
		0 => __('Concept pricequote', 'wp_wefact'),
		1 => __('Waiting queue', 'wp_wefact'),
		2 => __('Send', 'wp_wefact'),
		3 => __('Accepted', 'wp_wefact'),
		4 => __('Invoice made', 'wp_wefact'),
		8 => __('Not accepted', 'wp_wefact'),
	);
	if (isset($status)) {
		return $statuses[$status];
	}
	else {
		return $statuses;
	}
}

function debtor_statuses($status = null) {
	$statuses = array(
		0 => __('Concept pricequote', 'wp_wefact'),
		1 => __('Waiting queue', 'wp_wefact'),
		2 => __('Send', 'wp_wefact'),
		3 => __('Accepted', 'wp_wefact'),
		4 => __('Invoice made', 'wp_wefact'),
		8 => __('Not accepted', 'wp_wefact'),
	);
	if (isset($status)) {
		return $statuses[$status];
	}
	else {
		return $statuses;
	}
}

function wefact_messages($num) {
	switch ($num) {
		case 1:
			$message = __('The WeFact API settings must be entered in to use this plugin.', 'wp_wefact');
			$class = 'notice';
		break;
		case 2:
			$message = __('Can\'t connect to the WeFact API, please check if API settings are filled in correctly.', 'wp_wefact');
			$class = 'notice';
		break;
		case 3:
			$message = __('The invoice has been marked as <strong>paid</strong>.', 'wp_wefact');
			$class = 'success';
		break;
		case 4:
			$message = __('The pricequote has been marked as <strong>accepted</strong>.', 'wp_wefact');
			$class = 'success';
		break;
		case 5:
			$message = __('The pricequote has been marked as <strong>declined</strong>.', 'wp_wefact');
			$class = 'success';
		break;
	}
	return array('class' => $class, 'message' => $message);
}

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
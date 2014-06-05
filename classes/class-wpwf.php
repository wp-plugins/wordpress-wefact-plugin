<?php
class WPWF {

	public static function price($number) {
		if (empty($number)) $number = 0;
		return '&euro; '.number_format($number, 2, ',', '.');
	}

	public static function percentage($number) {
		return ($number * 100).'%';
	}

	public static function dmy($date) {
		$timestamp = strtotime($date);
		return date('d-m-Y', $timestamp);
	}

	public static function fulldate($date) {
		$timestamp = strtotime($date);
		$day = date('d', $timestamp);
		$month = date('m', $timestamp);
		$year = date('m', $timestamp);
	}

	public static function invoice_statuses($status = null) {
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

	public static function pricequote_statuses($status = null) {
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

	public static function debtor_statuses($status = null) {
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

	public static function messages($num) {
		$num = ($num ? $num : $_GET['msg']);
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

	public static function open_form($action, $method = "POST", $attr = array()) {
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

	public static function close_form() {
		echo '</form>';
	}

	public static function get_admin_tabs() {
	    global $submenu;
	    $options = array('<h2 class="nav-tab-wrapper">');
	    if ( is_array( $submenu ) && isset( $submenu['wefact'] ) ) {
	        foreach ( (array) $submenu['wefact'] as $item) {
	            if ( 'wefact' == $item[2] || $parent == $item[2] )
	                continue;
	            // 0 = name, 1 = capability, 2 = file
	            if ( current_user_can($item[1]) ) {
	            	$class = ( $_GET['page'] == $item[2] ? 'nav-tab-active' : '');
	                $options[] = '<a class="nav-tab '.$class.'" href="admin.php?page='.$item[2].'">'.$item[0].'</a>';
	                // $options[] = $item[2];
	            }
	        }
	    }
	    $options[] = '</h2>';
	    return implode("\n", $options);
	}

	public static function get_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			return $_SERVER['REMOTE_ADDR'];
		}
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
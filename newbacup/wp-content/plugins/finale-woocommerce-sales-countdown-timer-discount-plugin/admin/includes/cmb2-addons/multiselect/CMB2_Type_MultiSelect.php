<?php

/**
 * CMB select field type
 *
 * @since  2.2.2
 *
 * @category  WordPress_Plugin
 * @package   CMB2
 * @author    WebDevStudios
 * @license   GPL-2.0+
 * @link      http://webdevstudios.com
 */
class CMB2_Type_WCCT_MultiSelect extends CMB2_Type_Multi_Base {

	public function render() {
		$a = $this->parse_args( 'wcct_multiselect', array(
			'class'   => 'cmb2_select',
			'name'    => $this->_name(),
			'id'      => $this->_id(),
			'desc'    => $this->_desc( true ),
			'options' => $this->concat_items(),
		) );

		$attrs = $this->concat_attrs( $a, array( 'desc', 'options' ) );


		return $this->rendered( sprintf( '<select%s>%s</select>%s', $attrs, $a['options'], $a['desc'] ) );
	}

	/**
	 * Generates html for concatenated items
	 * @since  1.1.0
	 *
	 * @param  array $args Optional arguments
	 *
	 * @return string        Concatenated html items
	 */
	public function concat_items( $args = array() ) {

		$field = $this->field;

		$method = isset( $args['method'] ) ? $args['method'] : 'select_option';
		unset( $args['method'] );

		$value = $field->escaped_value() ? $field->escaped_value() : $field->get_default();

		$concatenated_items = '';

		$i = 1;

		$options = array();
		if ( $option_none = $field->args( 'show_option_none' ) ) {
			$options[''] = $option_none;
		}

		$options = $options + (array) $field->options();

		/** Adding db values in options if not present */
		if ( is_array( $value ) && count( $value ) > 0 ) {
			foreach ( $value as $val ) {
				if ( ! isset( $options[ $val ] ) || empty( $options[ $val ] ) ) {
					$options[ $val ] = WCCT_Common::get_the_title( $val );
				}
			}
		}

		foreach ( $options as $opt_value => $opt_label ) {

			// Clone args & modify for just this item
			$a = $args;

			$a['value'] = $opt_value;
			$a['label'] = $opt_label;

			// Check if this option is the value of the input
			if ( is_array( $value ) && count( $value ) > 0 ) {
				if ( in_array( $opt_value, $value ) ) {
					$a['checked'] = 'checked';
				}
			}
			$concatenated_items .= $this->$method( $a, $i ++ );
		}

		return $concatenated_items;
	}

}

<?php
/**
 * The Forminator_Template_Contact_Form class.
 *
 * @package Forminator
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Forminator_Template_Contact_Form
 *
 * @since 1.0
 */
class Forminator_Template_Contact_Form extends Forminator_Template {

	/**
	 * Template defaults
	 *
	 * @since 1.0
	 * @return array
	 */
	public function defaults() {
		return array(
			'id'          => 'contact_form',
			'name'        => esc_html__( 'Contact Form', 'forminator' ),
			'description' => esc_html__( 'Allows users to easily get in touch with queries or comments, typically including fields for name, email, subject, and message.', 'forminator' ),
			'icon'        => 'clipboard-notes',
			'priority'    => 2,
			'category'    => 'customer-service',
			'pro'         => false,
		);
	}

	/**
	 * Template fields
	 *
	 * @since 1.0
	 * @return array
	 */
	public function fields() {
		return array(
			array(
				'wrapper_id' => 'wrapper-1511347711918-1669',
				'fields'     => array(
					array(
						'element_id'        => 'name-1',
						'type'              => 'name',
						'cols'              => '12',
						'required'          => 'true',
						'field_label'       => esc_html__( 'First Name', 'forminator' ),
						'placeholder'       => esc_html__( 'E.g. John', 'forminator' ),
						'prefix_label'      => esc_html__( 'Prefix', 'forminator' ),
						'fname_label'       => esc_html__( 'First Name', 'forminator' ),
						'fname_placeholder' => esc_html__( 'E.g. John', 'forminator' ),
						'mname_label'       => esc_html__( 'Middle Name', 'forminator' ),
						'mname_placeholder' => esc_html__( 'E.g. Smith', 'forminator' ),
						'lname_label'       => esc_html__( 'Last Name', 'forminator' ),
						'lname_placeholder' => esc_html__( 'E.g. Doe', 'forminator' ),
					),
				),
			),
			array(
				'wrapper_id' => 'wrapper-1511347712118-1739',
				'fields'     => array(
					array(
						'element_id'      => 'email-1',
						'type'            => 'email',
						'cols'            => '12',
						'required'        => 'true',
						'field_label'     => esc_html__( 'Email Address', 'forminator' ),
						'placeholder'     => esc_html__( 'E.g. john@doe.com', 'forminator' ),
						'validation'      => true,
						'validation_text' => '',
					),
				),
			),
			array(
				'wrapper_id' => 'wrapper-1311247712118-1194',
				'fields'     => array(
					array(
						'element_id'            => 'phone-1',
						'type'                  => 'phone',
						'cols'                  => '12',
						'required'              => false,
						'field_label'           => esc_html__( 'Phone Number', 'forminator' ),
						'placeholder'           => esc_html__( 'E.g. +1 3004005000', 'forminator' ),
						'validation'            => 'none',
						'phone_validation_type' => 'standard',
						'validation_text'       => '',
					),
				),
			),
			array(
				'wrapper_id' => 'wrapper-1988247712118-9871',
				'fields'     => array(
					array(
						'element_id'  => 'textarea-1',
						'type'        => 'textarea',
						'cols'        => '12',
						'required'    => false,
						'field_label' => esc_html__( 'Message', 'forminator' ),
						'placeholder' => esc_html__( 'Enter your message...', 'forminator' ),
						'input_type'  => 'paragraph',
						'limit'       => '180',
						'limit_type'  => 'characters',
					),
				),
			),
		);
	}

	/**
	 * Template settings
	 *
	 * @since 1.0
	 * @return array
	 */
	public function settings() {
		return array(
			'form-type'                         => 'default',
			'submission-behaviour'              => 'behaviour-thankyou',
			'thankyou-message'                  => esc_html__( 'Thank you for contacting us, we will be in touch shortly.', 'forminator' ),
			'submitData'                        => array(
				'custom-submit-text'          => esc_html__( 'Send Message', 'forminator' ),
				'custom-invalid-form-message' => esc_html__( 'Error: Your form is not valid, please fix the errors!', 'forminator' ),
			),
			'enable-ajax'                       => 'true',
			'validation-inline'                 => true,
			'fields-style'                      => 'open',
			'basic-fields-style'                => 'open',
			'form-expire'                       => 'no_expire',
			// Main container.
			'form-padding-top'                  => '0',
			'form-padding-right'                => '0',
			'form-padding-bottom'               => '0',
			'form-padding-left'                 => '0',
			'form-border-width'                 => '0',
			'form-border-style'                 => 'none',
			'form-border-radius'                => '0',
			// Typography - Label.
			'cform-label-font-family'           => 'Roboto',
			'cform-label-custom-family'         => '',
			'cform-label-font-size'             => '12',
			'cform-label-font-weight'           => 'bold',
			// Typography - Section Title.
			'cform-title-font-family'           => 'Roboto',
			'cform-title-custom-family'         => '',
			'cform-title-font-size'             => '45',
			'cform-title-font-weight'           => 'normal',
			'cform-title-text-align'            => 'left',
			// Typography - Section Subtitle.
			'cform-subtitle-font-family'        => 'Roboto',
			'cform-subtitle-custom-font'        => '',
			'cform-subtitle-font-size'          => '18',
			'cform-subtitle-font-weight'        => 'normal',
			'cform-subtitle-text-align'         => 'left',
			// Typography - Input & Textarea.
			'cform-input-font-family'           => 'Roboto',
			'cform-input-custom-font'           => '',
			'cform-input-font-size'             => '16',
			'cform-input-font-weight'           => 'normal',
			// Typography - Radio & Checkbox.
			'cform-radio-font-family'           => 'Roboto',
			'cform-radio-custom-font'           => '',
			'cform-radio-font-size'             => '14',
			'cform-radio-font-weight'           => 'normal',
			// Typography - Select.
			'cform-select-font-family'          => 'Roboto',
			'cform-select-custom-family'        => '',
			'cform-select-font-size'            => '16',
			'cform-select-font-weight'          => 'normal',
			// Typography - Multi Select.
			'cform-multiselect-font-family'     => 'Roboto',
			'cform-multiselect-custom-font'     => '',
			'cform-multiselect-font-size'       => '16',
			'cform-multiselect-font-weight'     => 'normal',
			// Typography - Multi Select tag.
			'cform-multiselect-tag-font-family' => 'Roboto',
			'cform-multiselect-tag-custom-font' => '',
			'cform-multiselect-tag-font-size'   => '12',
			'cform-multiselect-tag-font-weight' => 'medium',
			// Typography - Dropdown.
			'cform-dropdown-font-family'        => 'Roboto',
			'cform-dropdown-custom-font'        => '',
			'cform-dropdown-font-size'          => '16',
			'cform-dropdown-font-weight'        => 'normal',
			// Typography - Calendar.
			'cform-calendar-font-family'        => 'Roboto',
			'cform-calendar-custom-font'        => '',
			'cform-calendar-font-size'          => '13',
			'cform-calendar-font-weight'        => 'normal',
			// Typography - Buttons.
			'cform-button-font-family'          => 'Roboto',
			'cform-button-custom-font'          => '',
			'cform-button-font-size'            => '14',
			'cform-button-font-weight'          => '500',
			// Typography - Timeline.
			'cform-timeline-font-family'        => 'Roboto',
			'cform-timeline-custom-font'        => '',
			'cform-timeline-font-size'          => '12',
			'cform-timeline-font-weight'        => 'normal',
			// Typography - Pagination.
			'cform-pagination-font-family'      => '',
			'cform-pagination-custom-font'      => '',
			'cform-pagination-font-size'        => '16',
			'cform-pagination-font-weight'      => 'normal',
			'payment_require_ssl'               => false,
			'submission-file'                   => 'delete',
			// Layout - Radio/Checkbox.
			'field-image-size'                  => 'custom',
			'cform-color-option'                => 'theme',
		);
	}
}
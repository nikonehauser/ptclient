<?php

namespace Tbmt;

class FormBuilder {
  private $formName;
  private $labels;
  private $values;
  private $errors;
  public function __construct($formName, array $labels = array(), array $values = array(), array $errors = array()) {
    $this->formName = $formName;
    $this->labels = $labels;
    $this->values = $values;
    $this->errors = $errors;
  }

  public function buildFieldGroup($fieldKey, $type = 'text', $label = null, $value = null, $error = null, array $options = array()) {
    if ( !$label )
      $label = Arr::init($this->labels, $fieldKey);

    if ( !$value )
      $value = Arr::init($this->values, $fieldKey);

    if ( !$error )
      $error = Arr::init($this->errors, $fieldKey);

    $className = '';
    if ( $error )
      $className .= ' has-error';

    $disabled = '';
    if ( !empty($options['disabled']) )
      $disabled = ' disabled="true"';

    $fieldClassName = 'field';
    if ( $type === 'checkbox' )
      $fieldClassName .= ' checkbox';

    $group = '<div class="'.$fieldClassName.' '.$className.'">';
    if ( $type === 'checkbox' ) {
      $checked = '';
      if ( $value )
        $checked = ' checked="checked"';

      $group .= '<label ><input type="'.$type.'" name="'.$fieldKey.'" value="1" '.$checked.$disabled.'>'.$label.'</label>';

    } else if ( $type === 'textarea' ) {
      $fieldId = $this->formName.$fieldKey;
      $group .= '<label for="'.$fieldId.'">'.$label.'</label>'.
        '<textarea class="fullwidth" cols="40" rows="3" id="'.$fieldId.'" name="'.$fieldKey.'"'.$disabled.' value="'.$value.'">'.$value.'</textarea>';

    } else {
      $fieldId = $this->formName.$fieldKey;
      $group .= '<label for="'.$fieldId.'">'.$label.'</label>'.
        '<input type="'.$type.'" id="'.$fieldId.'" name="'.$fieldKey.'" value="'.$value.'"'.$disabled.'>';

    }

    $group .= '<p class="help-block">'.$error.'</p>';
    if ( $error ) {
    }

    $group .= '</div>';
    return $group;
  }

  public function buildInvitationTypeSelectGroup($fieldKey, $loginType) {
    $label = Arr::init($this->labels, $fieldKey);
    $value = Arr::init($this->values, $fieldKey);
    $error = Arr::init($this->errors, $fieldKey);

    $memberTypes = Localizer::get('common.member_types');

    $fieldId = $this->formName.$fieldKey;

    if ( $loginType > \Member::TYPE_MARKETINGLEADER )
      $loginType = \Member::TYPE_MARKETINGLEADER;

    $group = '<div class="field">'.
      '<label for="'.$fieldId.'">'.$label.'</label>'.
      '<select name="'.$fieldKey.'" id="'.$fieldId.'" >';
    for ( $i = $loginType; $i >= \Member::TYPE_MEMBER; $i-- ) {
      if ( $i === \Member::TYPE_SUB_PROMOTER && $loginType < \Member::TYPE_MARKETINGLEADER )
        continue;

      $selected = '';
      if ( $value == $i )
        $selected = 'selected="selected"';

      $group .= '<option value="'.$i.'" '.$selected.'>'.$memberTypes[$i].'</option>';
    }

    $group .= '</select></div>';
    return $group;
  }

  public function buildBonusLevelSelectGroup($fieldKey) {
    $label = Arr::init($this->labels, $fieldKey);
    $value = Arr::init($this->values, $fieldKey);
    $error = Arr::init($this->errors, $fieldKey);

    $fieldId = $this->formName.$fieldKey;

    $group = '<div class="field">'.
      '<label for="'.$fieldId.'">'.$label.'</label>'.
      '<select name="'.$fieldKey.'">';
    for ( $i = 1; $i <= 20; $i++ ) {
      $group .= '<option value="'.$i.'">+ '.$i.'</option>';
    }

    $group .= '</select></div>';
    return $group;
  }
}

?>
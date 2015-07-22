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

  public function buildFieldGroup($fieldKey, $type = 'text', $label = '', $value = '', $error = '') {
    if ( !$label )
      $label = Arr::init($this->labels, $fieldKey);

    if ( !$value )
      $value = Arr::init($this->values, $fieldKey);

    if ( !$error )
      $error = Arr::init($this->errors, $fieldKey);

    $className = '';
    if ( $error )
      $className .= ' validation-error';

    $fieldClassName = 'field';
    if ( $type === 'checkbox' )
      $fieldClassName .= ' checkbox';

    $group = '<div class="'.$fieldClassName.'">';
    if ( $type === 'checkbox' ) {
      $checked = '';
      if ( $value )
        $checked = ' checked="checked"';

      $group .= '<label class="'.$className.'"><input type="'.$type.'" name="'.$fieldKey.'" value="1" '.$checked.' >'.$label.'</label>';

    } else {
      $fieldId = $this->formName.$fieldKey;
      $group .= '<label for="'.$fieldId.'">'.$label.'</label>'.
        '<input type="'.$type.'" class="'.$className.'" id="'.$fieldId.'" name="'.$fieldKey.'" value="'.$value.'">';

    }

    if ( $error ) {
      $group .= '<p class="help-block text-danger">'.$error.'</p>';
    }

    $group .= '</div>';
    return $group;
  }

  public function buildInvitationTypeSelectGroup($fieldKey, $offType) {
    $label = Arr::init($this->labels, $fieldKey);
    $value = Arr::init($this->values, $fieldKey);
    $error = Arr::init($this->errors, $fieldKey);

    $memberTypes = Localizer::get('common.member_types');

    $fieldId = $this->formName.$fieldKey;
    $offType--;

    $group = '<div class="field">'.
      '<label for="'.$fieldId.'">'.$label.'</label>'.
      '<select name="'.$fieldKey.'">';
    for ( $i = $offType; $i > \Member::TYPE_MEMBER; $i-- ) {
      $group .= '<option value="'.$i.'">'.$memberTypes[$i].'</option>';
    }

    $group .= '</select></div>';
    return $group;
  }
}

?>
<?php

/**
 * A field type for cloning an item in a multi item region
 *
 * @package default
 * @author Nils Mielke, FEUERWASSER
 * @version v0.1 - 2022-12-08
 */
class PerchFieldType_frwssr_moveitem extends PerchAPI_FieldType
{

    /**
     * Form fields for the edit page
     *
     * @param array $details
     * @return string
     */
    public function render_inputs($details = array())
    {
        $ftPath = PERCH_LOGINPATH . '/addons/fieldtypes/frwssr_moveitem/';
        $perch = Perch::fetch();
        $perch->add_javascript($ftPath . 'init.js?v=20221208');

        $id = $this->Tag->input_id();
        $hint = $this->Tag->hint() ? $this->Tag->hint() : '↗️ Move item ⚠️';
        $unsetfields = $this->Tag->unsetfields() ? ' data-unsetfields="' . $this->Tag->unsetfields() . '"': '';
        $styles = $this->Tag->styles() ? ' style="' . $this->Tag->styles() . '"' : ' style="background: slategray"';
        $moveto = $this->Tag->moveto() ? $this->Tag->moveto(): false;
        $array = [
            [
                'value' => '',
                'label' => $hint
            ]
        ];
        if(count($regions = explode(',', $moveto))):
            foreach($regions as $region):
                if(count($parts = explode('|', $region))):
                    list($value, $label) = $parts;
                    $disabled = $_GET['id'] == $value ? true : false;
                    $array[] = [                            
                        'value' => intval($value),
                        'label' => $label,
                        'disabled' => $disabled                            
                    ];
                else:
                    // error
                endif;    
            endforeach;
        else:
            if(count($region = explode('|', $moveto))):
                list($value, $label) = $region;
                $array[0]['value'] = intval($value);
                $array[0]['label'] = $label;
            else:
                // error
            endif;
        endif;
        if($moveto):
            $s = $this->Form->select($id, $array, '', $class='frwssr_moveitem__button button button-simple', $multiple=false, $attributes='data-path="' . $ftPath . '"' . $unsetfields . $moveto . $styles);
        else:
            // error
        endif;
        return $s;
    }

}
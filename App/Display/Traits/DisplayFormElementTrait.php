<?php

declare(strict_types=1);

trait DisplayFormElementTrait
{
    protected function options(?CollectionInterface $obj = null, ?string $valueName = null, ?string $contentName = null, ?FormBuilder $frm = null) : array
    {
        $options = [];
        if (!is_null($obj) && $obj->count() > 0) {
            $options[] = (new Option(['value' => '-1', 'content' => '']))->selected(true)->disable(true);
            foreach ($obj as $item) {
                $options[] = new Option([
                    'value' => $item->$valueName,
                    'content' => $item->$contentName,
                ]);
            }
        }
        return [
            $frm->selectOptions([], $options),
        ];
    }

    protected function inputHidden(FormBuilder $frm, array $inputHidden = [], ?object $product = null) : string
    {
        $inputHtml = '';
        foreach ($inputHidden as $input) {
            if ($product !== null) {
                $inputHtml .= $frm->input([
                    HiddenType::class => ['name' => $input, 'class' => [$input], 'id' => $input],
                ])->value($product->$input)->noLabel()->noWrapper()->html();
            }
        }
        return $inputHtml;
    }

    protected function inputs(string $template, FormBuilder $frm, ?object $product = null, array $arrInputs = []) : string
    {
        foreach ($arrInputs as $key => $arrInput) {
            $method = match ($key) {
                'text' => 'inputText',
                'textarea' => 'inputTextArea',
                'number' => 'inputNumber',
                'checkbox' => 'inputCheckbox',
                'select' => 'inputSelect',
                'singleInputText' => 'singleInput',
                'checkbox_iterate' => 'checkboxIterate'
            };
            foreach ($arrInput as $input => $attr) {
                $template = $this->$method($template, $input, $frm, $attr, $product);
            }
        }
        return $template;
    }

    protected function singleInput(string $template, string $input, FormBuilder $frm, array $attr, ?object $product = null)
    {
        return str_replace(
            '{{' . $attr['htmlPlace'] . '}}',
            $frm->input([
                TextType::class => ['name' => $input],
            ])->value($product !== null ? $product->$input : '')
                ->noWrapper()
                ->noLabel()
                ->placeholder($attr['placeholder'])
                ->class(array_merge($attr['fieldClass'], [$input]))
                ->labelClass($attr['labelClass'])
                ->id($input)
                ->req(false)
                ->attr($attr['formAttr'])
                ->html(),
            $template
        );
    }

    protected function inputText(string $template, string $input, FormBuilder $frm, array $attr, ?object $product = null)
    {
        $frm = $this->initializeInput($frm->input([
            TextType::class => ['name' => $input],
        ]), $attr);
        return str_replace(
            '{{' . $attr['htmlPlace'] . '}}',
            $frm->value($product !== null ? $product->$input : '')
                ->id($input)
                ->class([$input])
                ->html(),
            $template
        );
    }

    protected function initializeInput(FormBuilder $frm, array $attr) : FormBuilder
    {
        $frm = $this->label($frm, $attr);
        $frm = $this->removeWrapperClass($frm, $attr);
        array_key_exists('wrapperClass', $attr) ? $frm->wrapperClass($attr['wrapperClass']) : '';
        array_key_exists('require', $attr) ? $frm->req($attr['require']) : '';
        array_key_exists('labelClass', $attr) ? $frm->labelClass($attr['labelClass']) : '';
        array_key_exists('formAttr', $attr) ? $frm->attr($attr['formAttr']) : '';
        array_key_exists('fieldClass', $attr) ? $frm->class($attr['fieldClass']) : '';
        return $frm;
    }

    protected function label(FormBuilder $frm, array $attr) : FormBuilder
    {
        if ($attr['label'] == false) {
            $frm->noLabel();
        } elseif (!empty($attr['label'])) {
            $frm->labelUp($attr['label']);
        } else {
            $frm->label($attr['label']);
        }
        return $frm;
    }

    protected function removeWrapperClass(FormBuilder $frm, array $attr) : FormBuilder
    {
        if (array_key_exists('removeWrapperClass', $attr)) {
            foreach ($attr['removeWrapperClass'] as $class) {
                $frm->removeWrapperClass($class);
            }
        }
        return $frm;
    }

    protected function inputTextArea(string $template, string $input, FormBuilder $frm, array $attr, ?object $product = null)
    {
        return str_replace(
            '{{' . $attr['htmlPlace'] . '}}',
            $frm->input([
                TextAreaType::class => ['name' => $input],
            ])->Labelup($attr['label'])
                ->id($input)
                ->rows(3)
                ->class(array_merge($attr['fieldClass'], [$input]))
                ->LabelClass($attr['labelClass'])
                ->value($product !== null ? $product->$input : '')
                ->html(),
            $template
        );
    }

    protected function inputNumber(string $template, string $input, FormBuilder $frm, array $attr, ?object $product = null)
    {
        return str_replace(
            '{{' . $attr['htmlPlace'] . '}}',
            $frm->input([
                NumberType::class => ['name' => $input],
            ])->value($product !== null ? $product->$input : '')
                ->labelUp($attr['label'])
                ->class(array_merge($attr['fieldClass'], [$input]))
                ->labelClass($attr['labelClass'])
                ->id($input)
                ->req(false)
                ->helpBlock($attr['helpBlock'])
                ->html(),
            $template
        );
    }

    protected function inputCheckbox(string $template, string $input, FormBuilder $frm, array $attr, ?object $product = null)
    {
        return str_replace(
            '{{' . $attr['htmlPlace'] . '}}',
            $frm->input([
                CheckBoxType::class => ['name' => $input],
            ])->labelUp($attr['label'])
                ->class(array_merge($attr['fieldClass'], [$input]))
                ->labelClass($attr['labelClass'])
                ->id($input)
                ->req(false)
                ->helpBlock($attr['helpBlock'])
                ->removeWrapperClass('mb-3')
                ->closestDivClass($attr['closestDivClass'])
                ->checked($attr['checked'])
                ->html(),
            $template
        );
    }

    protected function checkboxIterate(string $template, string $input, FormBuilder $frm, array $attr)
    {
        /** @var CollectionInterface */
        $obj = $attr['options']['object'];
        $html = '';
        $frm->input([
            CheckBoxType::class => ['name' => $input],
        ])->labelClass($attr['labelClass'])->closestDivClass($attr['closestDivClass']);
        if ($obj->count() > 0) {
            foreach ($obj as $item) {
                $frm->value($item->{$attr['options']['id']});
                $frm->label($item->{$attr['options']['content']});
                $frm->id(rtrim($input, '[]') . $item->{$attr['options']['id']});
                $frm->class([$input]);
                $html .= $frm->html();
            }
        }
        return str_replace('{{' . $attr['htmlPlace'] . '}}', $html, $template);
    }

    protected function inputSelect(string $template, string $input, FormBuilder $frm, array $attr)
    {
        $name = array_key_exists('formAttr', $attr) && in_array('multiple', $attr['formAttr']) ? $input . '[]' : $input;
        $frm->input(
            [SelectType::class => ['name' => $name]],
            $this->options($attr['options']['object'], $attr['options']['id'], $attr['options']['content'], $frm)
        );
        $attr['label'] == false ? $frm->noLabel() : $frm->labelUp($attr['label']);
        array_key_exists('placeholder', $attr) ? $frm->placeholder($attr['placeholder']) : '';
        array_key_exists('wrapperClass', $attr) ? $frm->wrapperClass($attr['wrapperClass']) : '';
        array_key_exists('formAttr', $attr) ? $frm->attr($attr['formAttr']) : '';
        return str_replace(
            '{{' . $attr['htmlPlace'] . '}}',
            $frm->class(array_merge($attr['fieldClass'], [$input]))
                ->labelClass($attr['labelClass'])
                ->id($input)
                ->req(false)
                ->helpBlock($attr['helpBlock'])
                ->html(),
            $template
        );
    }
}
<?php

namespace Thinktomorrow\Chief\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\Common\Translatable\TranslatableCommand;

class PageUpdateRequest extends FormRequest
{
    use TranslatableCommand;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('chief')->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $translations = $this->request->get('trans');
        foreach ($translations as $locale => $trans)
        {
            if ($this->isCompletelyEmpty(['title', 'content', 'short'], $trans) && $locale !== app()->getLocale())
            {
                unset($translations[$locale]);
                $this->request->set('trans', $translations);
                continue;
            }

            $rules['trans.' . $locale . '.title']   = 'required|max:200';
            $rules['trans.' . $locale . '.short']   = 'max:700';
            $rules['trans.' . $locale . '.content'] = 'required|max:1500';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'trans.*.title'     => 'Title',
            'trans.*.slug'      => 'Permalink',
            'trans.*.content'   => 'Content',
            'trans.*.short'     => 'Short',
        ];
    }
}

<?php

namespace Modules\Media\Validators;

use Illuminate\Contracts\Validation\Rule;

class AvailableExtensionsRule implements Rule
{
  

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
      $intersection = array_diff(is_array($value) ? $value : [$value],mediaExtensionsAvailable());
 
      if(!empty($intersection))  return false;
      else return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans("media::messages.invalidExtensions");
    }
}

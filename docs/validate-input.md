### Validate user input

You can validate user input by using these custom rules

* `\Tarre\Laravel46Elks\Rules\E164NumberRule` [Read more about e164 here](https://46elks.se/kb/e164)
* `Tarre\Laravel46Elks\Rules\TextSenderIdRule` [Read more about Text Sender ID here](https://46elks.se/kb/text-sender-id)

Example of implementation
```PHP
<?php

namespace App\Http\Requests;

use Tarre\Laravel46Elks\Rules\E164NumberRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'phone_number' => ['required', new E164NumberRule],
        ];
    }
}

```

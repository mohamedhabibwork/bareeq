<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;

trait RequestMethods
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
     * @return float|int
     */
    public function getPostMaxSize(): float|int
    {
        if (is_numeric($postMaxSize = ini_get('post_max_size'))) {
            return (int)$postMaxSize;
        }

        $metric = strtoupper(substr($postMaxSize, -1));
        $postMaxSize = (int)$postMaxSize;

        return match ($metric) {
            'K' => $postMaxSize * 1024,
            'M' => $postMaxSize * 1048576,
            'G' => $postMaxSize * 1073741824,
            default => $postMaxSize,
        };
    }

    /**
     * @return array
     */
    public function validatedData(): array
    {
        $this->removeNullFromRequest();

        $validated = $this->validator->validated();

        $this->filesUpload($validated);
        $this->encryption($validated);

        return $validated;
    }

    /**
     * @return void
     */
    public function removeNullFromRequest(): void
    {
        foreach ($this->forgetIfNull ?? [] as $item)
            if ($this->has($item) && (blank($this->get($item)) || is_null($this->get($item))))
                $this->request->remove($item);
    }

    public function filesUpload(&$validated): void
    {
        foreach ($this->filesKeys ?? [] as $key) {
            if (!$this->hasFile($key)) continue;

            if (is_array($this->file($key))) {
                $files = [];
                foreach ($this->file($key) as $file) $files[] = uploader($file);
                $validated[$key] = $files;
            } else {
                $validated[$key] = uploader($this->file($key));
            }

        }
    }

    public function encryption(&$validated): void
    {
        if (!property_exists($this, 'encryption')) return;

        foreach ($this->encryption as $encoding) {
            $value = Arr::get($validated, $encoding);
            if (isset($value))
                Arr::set($validated, $encoding, bcrypt($value));
        }
    }
}

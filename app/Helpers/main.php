<?php

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('fileLangDatatable')) {
    /**
     * @return string
     */
    function fileLangDatatable(): string
    {
        $lang = [
                'ar' => 'Arabic',
                'en' => 'English'
            ][current_local()] ?? 'English';

        return url("//cdn.datatables.net/plug-ins/1.10.12/i18n/{$lang}.json");
    }
}
if (!function_exists('uploader')) {
    /**
     * @param $input
     * @param string $folder
     * @param array $validation
     * @return string
     */
    function uploader($input, string $folder = "", array $validation = []): string
    {
        $request = request();
        $isFile = $input instanceof UploadedFile;

        // remove any / char form var
        $path = rtrim($folder, '/');
        $defaultDir = "uploads";
        // validate Image
        if (!$isFile) {
            if (empty($validation)) $request->validate([$input => ['required', 'image', 'mimes:jpeg,jpg,png']]);
            else $request->validate([$input => $validation]);
        }

        // get file if not getting before
        $file = $isFile ? $input : $request->file($input);

        // this line if true throw Exception 400 with errors
        if (blank($file->getClientOriginalExtension())) response()->json(["status" => false, "errors" => [(is_string($input) ? $input : "file") => "file without Extension try by other way please ♥."]], 400)->send();

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $disk = Storage::build([
            'driver' => 'local',
            'root' => public_path("$defaultDir"),
            'url' => env('APP_URL') . "/$defaultDir",
            'visibility' => 'public',
            'throw' => false,
            'permissions' => [
                'file' => [
                    'public' => 0644,
                    'private' => 0600,
                ],
                'dir' => [
                    'public' => 0755,
                    'private' => 0700,
                ],
            ],
        ]);

        $disk->put("$path/$filename", $file->getContent());

        return str_replace('//', '/', "$defaultDir/" . $path . '/' . $filename);
    }
}

if (!function_exists('locals')) {
    /**
     * @return string[]
     */
    function locals(): array
    {
        return ['ar', 'en',];
    }
}

if (!function_exists('current_local')) {
    /**
     * @return string
     */
    function current_local(): string
    {
        return app()->getLocale();
    }
}

if (!function_exists('current_dir')) {
    /**
     * @return string
     */
    function current_dir(): string
    {
        return current_local() == "ar" ? 'rtl' : 'ltr';
    }
}

if (!function_exists('default_time_zone')) {
    /**
     * @return string
     */
    function default_time_zone(): string
    {
        return 'Africa/Cairo';
    }
}

if (!function_exists('check_image_exists_or_default')) {
    /**
     * @param string|null $imageUrl
     * @return string
     */
    function check_image_exists_or_default(?string $imageUrl = null): string
    {
        // check is url
        if (filter_var($imageUrl, FILTER_VALIDATE_URL) === true) return $$imageUrl;

        $path = public_path(str_replace(asset(''), '', $imageUrl ?? ''));
        $default = config('app.image.default', asset('assets/dashboard/img/AdminLTELogo.png'));

        if (!file_exists($path)) return $default;

        return $imageUrl ?? $default;
    }
}
// qu
/**
 * @param string $name
 * @param $default
 * @param string|null $type
 * @param string|null $group_by
 * @param string|null $locale
 * @return string
 */
function setting(string $name, $default = '', string $type = null, string $group_by = null, string $locale = null): string
{

    $locale = $locale ?? substr(app()->getLocale(), 0, 2);
    $type = $type ?? 'string';
    if (cache()->has('settings')) {
        $settings = cache()->get('settings');
    } else {
        $settings = cache()->remember('settings', now()->addMinutes(5), function () {
            return Setting::all();
        });
    }

    if ($setting = $settings->where('name', $name)->firstWhere('locale', '=', $locale)) {
        return $setting->value ?? $default;
    }

    return Setting::firstOrCreate(
            ['name' => $name, 'locale' => $locale],
            ['name' => $name, 'type' => $type, 'locale' => $locale, 'value' => $default ?? $name, 'group_by' => $group_by]
        )->value ?? $default;
}

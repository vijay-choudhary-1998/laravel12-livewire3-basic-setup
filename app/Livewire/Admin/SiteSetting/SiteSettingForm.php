<?php

namespace App\Livewire\Admin\SiteSetting;

use App\Helpers\ImageHelper;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SiteSetting;
use App\Models\Upload;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin-app')]
class SiteSettingForm extends Component
{
    use WithFileUploads;

    public $site_name, $site_email, $footer_text, $site_logo, $existing_logo;

    public $key = [], $value = [];

    public $favicon;

    public $settings;

    public function mount()
    {
        $this->settings = $settings = SiteSetting::pluck('value', 'key');

        if ($this->settings) {
            $this->key['site_name'] = $settings['site_name'] ?? '';
            $this->key['site_email'] = $settings['site_email'] ?? '';
            $this->key['footer_text'] = $settings['footer_text'] ?? '';
            $this->key['existing_logo'] = $settings['site_logo'] ?? '';
            $this->key['site_status'] = $settings['site_status'] ?? 'live';
            $this->key['timezone'] = $settings['timezone'] ?? config('app.timezone');
            $this->key['language'] = $settings['language'] ?? 'en';
            $this->key['existing_favicon'] = $settings['favicon'] ?? '';

        }
    }

    public function save()
    {
        $this->validate($this->rules());


        if (!empty($this->favicon)) {
            $faviconImage = $this->favicon;
            $path = 'uploads/site-icons';
            $origPath = $faviconImage->store($path, 'public_root');

            $avifPath = ImageHelper::convertToAvif($origPath, $path);

            Upload::create([
                'original_name' => $faviconImage->getClientOriginalName(),
                'avif_path' => $avifPath,
            ]);

            $faviconPath = $avifPath;
        } else {
            $faviconPath = $this->key['existing_favicon'];
        }
        $this->key['favicon'] = $this->key['existing_favicon'] = $faviconPath;

        if (!empty($this->site_logo)) {
            $image = $this->site_logo;
            $path = 'uploads/site-logos';
            $origPath = $image->store($path, 'public_root');

            $avifPath = '';
            $avifPath = ImageHelper::convertToAvif($origPath, $path);

            $upload = Upload::create([
                'original_name' => $image->getClientOriginalName(),
                'avif_path' => $avifPath,
            ]);

            $logoPath = $avifPath;
        } else {
            $logoPath = $this->key['existing_logo'];
        }

        $this->key['site_logo'] = $this->key['existing_logo'] = $logoPath;

        foreach ($this->key as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                ]
            );
        }

        $this->dispatch('swal:toast', ['type' => 'success', 'title' => '', 'message' => 'Settings updated successfully.']);
    }

    public function rules()
    {
        return [
            'key' => 'required|array',
            'key.site_name' => 'required|string|max:255',
            'key.site_email' => 'required|email|max:255',
            'key.footer_text' => 'nullable|string',
            'site_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'key.site_status' => 'required|in:live,maintenance',
            'key.timezone' => 'required|string',
            'key.language' => 'required|string|max:5',
            'favicon' => 'nullable|image|mimes:jpg,jpeg,png,ico,webp|max:512',

        ];

    }
    public function render()
    {
        return view('livewire.admin.site-setting.site-setting-form');
    }
    public function clearCache()
    {
        foreach (SiteSetting::pluck('key') as $key) {
            Cache::forget("setting_{$key}");
        }

        Cache::forget('site_settings_all');

        $this->dispatch('swal:toast', [
            'type' => 'success',
            'title' => '',
            'message' => 'Site settings cache cleared successfully.',
        ]);
    }

}

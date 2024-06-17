<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckTranslations extends Command
{
    protected $signature = 'translations:check';
    protected $description = 'Check and add missing translation keys to the JSON language files';

    public function handle()
    {
        $appDirectory = app_path();
        $translationKeys = $this->getTranslationKeys($appDirectory);
        $this->updateLanguageFiles($translationKeys);

        $this->info('Translation keys checked and updated.');
    }

    protected function getTranslationKeys($directory)
    {
        $files = File::allFiles($directory);
        $translationKeys = [];

        foreach ($files as $file) {
            if ($file->getExtension() == 'php') {
                $content = File::get($file->getRealPath());
                preg_match_all('/__\([\'"]([^\'"]+)[\'"]\)/', $content, $matches);

                if (!empty($matches[1])) {
                    $translationKeys = array_merge($translationKeys, $matches[1]);
                }
            }
        }

        return array_unique($translationKeys);
    }

    protected function updateLanguageFiles($translationKeys)
    {
        $languages = ['en','ar']; // Add other language codes if needed

        foreach ($languages as $lang) {
            $langFilePath = resource_path("lang/{$lang}.json");
            $translations = [];

            if (File::exists($langFilePath)) {
                $translations = json_decode(File::get($langFilePath), true);
            }

            $newKeys = array_diff($translationKeys, array_keys($translations));

            foreach ($newKeys as $key) {
                $translations[$key] = $key; // Default value is the key itself, you can modify as needed
            }

            File::put($langFilePath, json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }
}

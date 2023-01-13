<?php

namespace Devaslanphp\AutoTranslate\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AutoTranslate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:translate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will search everywhere in your code for translations to automatically generate JSON files for you.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $locales = config('auto-translate.locales');
        foreach ($locales as $locale) {
            try {
                Artisan::call('translatable:export ' . $locale);
                $filePath = lang_path($locale . '.json');
                if (File::exists($filePath)) {
                    $this->info('Translating ' . $locale . ', please wait...');
                    $results = [];
                    $localeFile = File::get($filePath);
                    $localeFileContent = array_keys(json_decode($localeFile, true));
                    $translator = new GoogleTranslate($locale);
                    $translator->setSource(config('app.fallback_locale'));
                    foreach ($localeFileContent as $key) {
                        $results[$key] = $translator->translate($key);
                    }
                    File::put($filePath, json_encode($results, JSON_UNESCAPED_UNICODE));
                }
            } catch (\Exception $e) {
                $this->error('Error: ' . $e->getMessage());
            }
        }
        return Command::SUCCESS;
    }
}

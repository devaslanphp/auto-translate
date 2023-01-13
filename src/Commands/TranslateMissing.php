<?php

namespace Devaslanphp\AutoTranslate\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslateMissing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Based on the config "auto-translate.base_locale" this command will generate missing translation from this JSON file to the other "auto-translate.locales" JSON files.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $base = config('auto-translate.base_locale');
        $locales = [];
        foreach (config('auto-translate.locales') as $locale) {
            if ($locale != $base) {
                $locales[] = $locale;
            }
        }
        $baseTranslations = json_decode(File::get(lang_path($base . '.json')), true);
        $this->info('Found ' . sizeof($locales) . ' locales. Performing, please wait...');
        $bar = $this->getOutput()->createProgressBar(sizeof($locales));
        $bar->start();
        foreach ($locales as $locale) {
            $filePath = lang_path($locale . '.json');
            if (File::exists($filePath)) {
                $localeTranslations = json_decode(File::get(lang_path($locale . '.json')), true);
                $translator = new GoogleTranslate($locale);
                $translator->setSource(config('app.fallback_locale'));
                $newLocaleTranslations = [];
                foreach ($baseTranslations as $kbt => $baseTranslation) {
                    try {
                        if (!array_key_exists($kbt, $localeTranslations)) {
                            $newLocaleTranslations[$kbt] = $translator->translate($kbt);
                        } else {
                            $newLocaleTranslations[$kbt] = $localeTranslations[$kbt];
                        }
                    } catch (\Exception $e) {
                        $this->error('Error: ' . $e->getMessage());
                        $newLocaleTranslations[$kbt] = $kbt;
                    }
                }
                File::put($filePath, json_encode($newLocaleTranslations, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
            }
            $bar->advance();
        }
        $bar->finish();
        return Command::SUCCESS;
    }
}

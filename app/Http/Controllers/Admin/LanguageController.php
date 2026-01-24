<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    public function langImport(Request $request)
    {
        // language code from request (ex: fr, en, ar)
        $lang = $request->input('lang', config('app.locale'));

        $langPath = resource_path("lang/{$lang}.json");

        // Create file if not exists
        if (!File::exists($langPath)) {
            File::put($langPath, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $existingItems = json_decode(File::get($langPath), true) ?? [];

        $text = $this->getKeys();
        $keywords = array_filter(array_map('trim', explode("\n", $text)));

        $newArr = [];

        foreach ($keywords as $keyword) {
            if (!array_key_exists($keyword, $existingItems)) {
                $newArr[$keyword] = $keyword;
            }
        }

        if (!empty($newArr)) {
            $result = array_merge($existingItems, $newArr);
            File::put(
                $langPath,
                json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }

        return response()->json([
            'status' => 'success',
            'added_keys' => count($newArr),
            'lang' => $lang
        ]);
    }

    public function getKeys()
    {
        $langKeys = [];
        $dirname = resource_path('views');

        foreach ($this->getAllFiles($dirname) as $file) {
            $langKeys = array_merge($langKeys, $this->getLangKeys($file));
        }

        $langKeys = array_unique($langKeys);

        return implode("\n", $langKeys);
    }
    private function getAllFiles($dir)
    {
        $files = [];

        $iter = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iter as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    private function getLangKeys($path)
    {
        $code = file_get_contents($path);

        preg_match_all("/@lang\(['\"](.*?)['\"]\)/", $code, $matches);

        return $matches[1] ?? [];
    }
}